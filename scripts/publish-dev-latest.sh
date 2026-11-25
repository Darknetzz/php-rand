#!/usr/bin/env bash
# Rolling "dev-latest" pre-release for the tip of `dev` (like jotty-android).
#   - GitHub: deletes and recreates the dev-latest pre-release + tag at the commit (gh).
#   - GitLab: force-moves the dev-latest tag and creates/updates the GitLab release
#     (in CI with CI_JOB_TOKEN; locally only the tag is moved, via the `gitlab` remote).
# Notes: commit links, the :dev Docker image and the CHANGELOG [Unreleased] section.
#
# Usage:
#   ./scripts/publish-dev-latest.sh [--dry-run] [--sha <commit>]
# Env:
#   GH_TOKEN / GITHUB_TOKEN   GitHub token with contents:write on GH_REPO (CI; locally `gh auth` works)
#   GH_REPO                   default Darknetzz/php-rand
#   GITLAB_REMOTE             local fallback remote for the tag (default gitlab)
#   WAIT_TIMEOUT              seconds to wait for GitHub `dev` to reach the commit (default 600)
set -euo pipefail

TAG="dev-latest"
DRY_RUN=0
SHA=""

while [[ $# -gt 0 ]]; do
  case "$1" in
    --dry-run) DRY_RUN=1; shift ;;
    --sha) SHA="${2:?--sha needs a commit}"; shift 2 ;;
    -h|--help) sed -n '2,15p' "$0" | sed 's/^# \{0,1\}//'; exit 0 ;;
    *) echo "Unknown option: $1" >&2; exit 1 ;;
  esac
done

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
cd "$REPO_ROOT"

export GH_REPO="${GH_REPO:-Darknetzz/php-rand}"
[[ -n "${GH_TOKEN:-}" || -z "${GITHUB_TOKEN:-}" ]] || export GH_TOKEN="$GITHUB_TOKEN"
GITLAB_REMOTE="${GITLAB_REMOTE:-gitlab}"
WAIT_TIMEOUT="${WAIT_TIMEOUT:-600}"

SHA="$(git rev-parse "${SHA:-HEAD}^{commit}")"
SHORT_SHA="${SHA:0:7}"
SUBJECT="$(git log -1 --format=%s "$SHA")"
VERSION="$(grep -E '^VERSION=' docker-image.config 2>/dev/null | cut -d= -f2 | tr -d '"' || true)"
IMAGE="$(grep -E '^IMAGE=' docker-image.config 2>/dev/null | cut -d= -f2 | tr -d '"' || true)"
GHCR_IMAGE="$(grep -E '^GHCR_IMAGE=' docker-image.config 2>/dev/null | cut -d= -f2 | tr -d '"' || true)"
IMAGE="${IMAGE:-darknetz/php-rand}"
GHCR_IMAGE="${GHCR_IMAGE:-ghcr.io/darknetzz/php-rand}"

BUILT_BY="published locally"
[[ -n "${CI_PIPELINE_URL:-}" ]] && BUILT_BY="published by [GitLab CI](${CI_PIPELINE_URL})"

notes_file="$(mktemp)"
changelog_file="$(mktemp)"
trap 'rm -f "$notes_file" "$changelog_file"' EXIT

# [Unreleased] notes (as of SHA) without the heading, the placeholder line and the trailing rule.
git show "${SHA}:CHANGELOG.md" >"$changelog_file"
UNRELEASED="$(bash "$SCRIPT_DIR/extract_changelog_section.sh" "$changelog_file" "[Unreleased]" \
  | sed -e '1{/^## \[Unreleased\]/d;}' -e '/^_Add entries here/d' -e '/^---[[:space:]]*$/d')"
[[ -n "${UNRELEASED//[[:space:]]/}" ]] || UNRELEASED="_No unreleased changes listed._"
cat >"$notes_file" <<EOF
Rolling pre-release from \`dev\` (${BUILT_BY}). Moves to every new \`dev\` commit.
⚠️ Preview build: may contain unstable or breaking changes. Use a \`v*\` release for production.

| Item | Value |
| --- | --- |
| Commit | [\`${SHORT_SHA}\`](https://github.com/${GH_REPO}/commit/${SHA}) ${SUBJECT} |
| Based on | ${VERSION:-unknown} + unreleased changes |
| Docker | \`docker pull ${IMAGE}:dev\` / \`docker pull ${GHCR_IMAGE}:dev\` |
| Changelog | [CHANGELOG.md](https://github.com/${GH_REPO}/blob/dev/CHANGELOG.md) |

${UNRELEASED}
EOF

if [[ "$DRY_RUN" -eq 1 ]]; then
  echo "[dry-run] Would point ${TAG} at ${SHORT_SHA} on GitHub (${GH_REPO}) and GitLab, with notes:"
  echo "-----"
  cat "$notes_file"
  exit 0
fi

command -v gh >/dev/null || { echo "gh CLI required" >&2; exit 1; }
gh api "repos/${GH_REPO}" --jq .full_name >/dev/null

# The GitHub release must target a commit GitHub already has. `dev` is pushed to both remotes,
# so wait for it; exit quietly if GitHub dev already moved past this commit (newer run publishes).
deadline=$((SECONDS + WAIT_TIMEOUT))
while :; do
  remote="$(gh api "repos/${GH_REPO}/commits/dev" --jq .sha 2>/dev/null || true)"
  [[ "$remote" == "$SHA" ]] && break
  if [[ -n "$remote" ]] && \
     [[ "$(gh api "repos/${GH_REPO}/compare/${SHA}...${remote}" --jq .status 2>/dev/null || true)" == "ahead" ]]; then
    echo "GitHub dev is at ${remote:0:7}, ahead of ${SHORT_SHA}; skipping (a newer run publishes)."
    exit 0
  fi
  if [[ $SECONDS -ge $deadline ]]; then
    echo "Timed out waiting for GitHub dev = ${SHORT_SHA} (currently ${remote:-missing}). Push dev to origin too." >&2
    exit 1
  fi
  sleep 10
done

gh release delete "$TAG" --cleanup-tag --yes 2>/dev/null || true
gh api -X DELETE "repos/${GH_REPO}/git/refs/tags/${TAG}" >/dev/null 2>&1 || true
gh release create "$TAG" \
  --target "$SHA" \
  --title "Dev Latest" \
  --notes-file "$notes_file" \
  --prerelease \
  --latest=false
echo "GitHub release ${TAG} -> ${SHORT_SHA}"

# GitLab: move the tag (job token needs "Allow Git push requests" under
# Settings > CI/CD > Job token permissions), then create or update the release.
if [[ -n "${CI_JOB_TOKEN:-}" && -n "${CI_SERVER_HOST:-}" ]]; then
  gitlab_url="${CI_SERVER_PROTOCOL:-https}://gitlab-ci-token:${CI_JOB_TOKEN}@${CI_SERVER_HOST}:${CI_SERVER_PORT:-443}/${CI_PROJECT_PATH}.git"
elif git remote get-url "$GITLAB_REMOTE" >/dev/null 2>&1; then
  gitlab_url="$GITLAB_REMOTE"
else
  echo "No GitLab remote '${GITLAB_REMOTE}'; skipping GitLab tag/release."
  exit 0
fi
if ! git push --quiet --force "$gitlab_url" "${SHA}:refs/tags/${TAG}"; then
  echo "ERROR: could not move the GitLab ${TAG} tag (job token push not allowed?)" >&2
  exit 1
fi
echo "GitLab tag ${TAG} -> ${SHORT_SHA}"

if [[ -z "${CI_JOB_TOKEN:-}" || -z "${CI_API_V4_URL:-}" ]]; then
  echo "Not in GitLab CI: GitLab release notes left as they are (the tag moved)."
  exit 0
fi
api="${CI_API_V4_URL}/projects/${CI_PROJECT_ID}/releases"
payload="$(jq -n --arg tag "$TAG" --arg desc "$(cat "$notes_file")" \
  '{tag_name: $tag, name: "Dev Latest", description: $desc}')"
resp="$(mktemp)"
gl_release() { # <method> <url> -> prints HTTP code, body in $resp
  curl -sS -o "$resp" -w '%{http_code}' -X "$1" -H "JOB-TOKEN: ${CI_JOB_TOKEN}" \
    -H 'Content-Type: application/json' --data "$payload" "$2"
}
# Create first (the job token can create releases); update when it already exists.
code="$(gl_release POST "$api")"
[[ "$code" == "409" ]] && code="$(gl_release PUT "${api}/${TAG}")"
if [[ "$code" =~ ^20[01]$ ]]; then
  echo "GitLab release ${TAG} updated"
else
  # Non-fatal: the GitLab release follows the moved tag; only its notes go stale.
  echo "WARNING: GitLab release API returned HTTP ${code}: $(head -c 300 "$resp")" >&2
fi
rm -f "$resp"
echo "Done: https://github.com/${GH_REPO}/releases/tag/${TAG}"
