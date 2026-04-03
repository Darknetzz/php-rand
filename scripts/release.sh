#!/usr/bin/env bash
# Rotate CHANGELOG.md [Unreleased] into [vX.Y.Z] (YYYY-MM-DD), reset Unreleased, bump docker-image.config,
# then optionally commit + tag + push, GitHub release (gh), and local Docker publish.
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
CHANGELOG="$ROOT/CHANGELOG.md"

# gh has no -C (unlike git). Use `command gh` so a user alias/function cannot inject broken flags (e.g. gh -C).
run_gh() {
  (cd "$ROOT" && command gh "$@")
}
DOCKER_CONFIG="$ROOT/docker-image.config"
DRY_RUN=0
PUBLISH_ONLY=0
VERSION_RAW=""
CREATE_GH_RELEASE="${CREATE_GH_RELEASE:-}"
PUBLISH_DOCKER="${PUBLISH_DOCKER:-}"
MERGE_RELEASE_TO_MAIN="${MERGE_RELEASE_TO_MAIN:-}"
MERGE_TO_MAIN_VIA_PR="${MERGE_TO_MAIN_VIA_PR:-}"
RELEASE_BRANCH="${RELEASE_BRANCH:-dev}"
MAIN_BRANCH="${MAIN_BRANCH:-main}"

# Read prompts from the controlling terminal so stdin is never drained by git/ssh (set -e would exit on failed read).
read_tty() {
  local prompt=$1
  local __v
  if [[ -r /dev/tty ]]; then
    IFS= read -r -p "$prompt" __v < /dev/tty || true
  else
    IFS= read -r -p "$prompt" __v || true
  fi
  printf '%s' "$__v"
}

usage() {
  echo "Usage: $(basename "$0") [X.Y.Z] [--dry-run] [--publish-only]"
  echo "  Rotates CHANGELOG.md ## [Unreleased] into ## [vX.Y.Z] (today's date), then resets Unreleased."
  echo "  Updates VERSION= in docker-image.config to match the release."
  echo "  With no version: prompts for the next release when stdin is a TTY; otherwise uses patch+1"
  echo "  of the first ## [vM.m.p] heading in CHANGELOG.md (newest section after [Unreleased])."
  echo "  Press Enter at the prompt to accept the suggested default."
  echo "  --dry-run        Write merged changelog to stdout only (no file writes, no git/docker)."
  echo "  --publish-only   Skip changelog/git; run GitHub release (gh) + docker-pushimage.sh for X.Y.Z"
  echo "                   (use after you already pushed the tag)."
  echo ""
  echo "  Environment:"
  echo "    CREATE_GH_RELEASE=1     After push, run: gh release create (skipped if release already exists)."
  echo "    PUBLISH_DOCKER=1        After push, run ./docker-pushimage.sh (Docker Hub + optional GHCR)."
  echo "    MERGE_RELEASE_TO_MAIN=1 After gh/docker, sync RELEASE_BRANCH into MAIN_BRANCH (see MERGE_TO_MAIN_VIA_PR)."
  echo "    MERGE_TO_MAIN_VIA_PR=1  Skip direct push; open a PR base=MAIN head=RELEASE (for protected main)."
  echo "    RELEASE_BRANCH / MAIN_BRANCH  Override branch names (defaults: dev, main)."
  echo "  Pushing a v* tag also triggers .github/workflows (GitHub Release + Docker); gh step is optional."
  exit "${1:-0}"
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    -h|--help) usage 0 ;;
    --dry-run) DRY_RUN=1; shift ;;
    --publish-only) PUBLISH_ONLY=1; shift ;;
    -*)
      echo "Unknown option: $1" >&2
      usage 1
      ;;
    *)
      if [[ -n "$VERSION_RAW" ]]; then
        echo "Extra argument: $1" >&2
        usage 1
      fi
      VERSION_RAW="$1"
      shift
      ;;
  esac
done

if [[ ! -f "$CHANGELOG" ]]; then
  echo "Changelog not found: $CHANGELOG" >&2
  exit 1
fi

if [[ "$PUBLISH_ONLY" -eq 1 ]]; then
  if [[ "$DRY_RUN" -eq 1 ]]; then
    echo "Cannot combine --publish-only with --dry-run." >&2
    exit 1
  fi
  if [[ -z "$VERSION_RAW" ]]; then
    echo "Usage: $(basename "$0") X.Y.Z --publish-only" >&2
    exit 1
  fi
fi

changelog_section_for_tag() {
  # Use string prefix match: "[v1.2.10]" in a regex is a character class, not a literal.
  awk -v ver="$VERSION" '
    BEGIN { capture = 0; found = 0; prefix = "## [v" ver "]" }
    /^## \[/ {
      if (capture == 1) { exit }
    }
    index($0, prefix) == 1 {
      capture = 1
      found = 1
    }
    capture == 1 { print }
    END { if (found == 0) exit 1 }
  ' "$CHANGELOG"
}

maybe_gh_release() {
  type -P gh &>/dev/null || { echo "gh (GitHub CLI) not on PATH; skip GitHub release CLI step." >&2; return 0; }
  local tmp_path
  tmp_path="$(mktemp)"
  if ! changelog_section_for_tag >"$tmp_path"; then
    rm -f "$tmp_path"
    echo "ERROR: Could not extract CHANGELOG section for v${VERSION}" >&2
    return 1
  fi
  if run_gh release view "v${VERSION}" &>/dev/null; then
    rm -f "$tmp_path"
    echo "GitHub release v${VERSION} already exists (skipping gh release create)."
    return 0
  fi
  if ! run_gh release create "v${VERSION}" --title "v${VERSION}" --notes-file "$tmp_path" --verify-tag; then
    rm -f "$tmp_path"
    return 1
  fi
  rm -f "$tmp_path"
  echo "Created GitHub release v${VERSION} via gh."
}

maybe_docker_push() {
  if [[ ! -f "$ROOT/docker-pushimage.sh" ]]; then
    echo "docker-pushimage.sh not found." >&2
    return 1
  fi
  (cd "$ROOT" && bash ./docker-pushimage.sh)
}

post_push_gh_prompt() {
  if [[ "$CREATE_GH_RELEASE" == "1" ]]; then
    maybe_gh_release || true
    return
  fi
  local yn
  yn="$(read_tty "Create GitHub release with gh? [Y/n] ")"
  if [[ -z "$yn" ]] || [[ "$yn" =~ ^[yY] ]]; then
    maybe_gh_release || true
  fi
}

post_push_docker_prompt() {
  if [[ "$PUBLISH_DOCKER" == "1" ]]; then
    maybe_docker_push || true
    return
  fi
  local yn
  yn="$(read_tty "Run ./docker-pushimage.sh (Docker Hub + optional GHCR)? [Y/n] ")"
  if [[ -z "$yn" ]] || [[ "$yn" =~ ^[yY] ]]; then
    maybe_docker_push || true
  fi
}

create_pr_release_to_main() {
  local rb="${RELEASE_BRANCH:-dev}"
  local mb="${MAIN_BRANCH:-main}"
  if ! type -P gh &>/dev/null; then
    echo "Install GitHub CLI (gh) or open a PR manually: ${rb} -> ${mb} on GitHub." >&2
    return 1
  fi
  git -C "$ROOT" fetch origin "$rb" "$mb" || return 1
  local existing
  existing="$(run_gh pr list -B "$mb" -H "$rb" -s open --json number -q '.[0].number // empty' 2>/dev/null || true)"
  if [[ -n "$existing" ]]; then
    echo "Open PR already exists for ${rb} -> ${mb}: #$existing"
    run_gh pr view "$existing" --web 2>/dev/null || true
    return 0
  fi
  local pr_body
  # Use \x60 for markdown backticks (avoids SC2016 / command-substitution parsing).
  pr_body="$(printf $'\x60%s\x60 into \x60%s\x60 after release **v%s**. Merge when checks pass (repository rules may require PR review).' "$rb" "$mb" "$VERSION")"
  run_gh pr create --base "$mb" --head "$rb" \
    --title "Release v${VERSION}: merge ${rb} into ${mb}" \
    --body "$pr_body"
}

maybe_merge_release_into_main() {
  local back="$1"
  local rb="${RELEASE_BRANCH:-dev}"
  local mb="${MAIN_BRANCH:-main}"
  if [[ "$back" != "$rb" ]]; then
    echo "Skip merge to $mb: not on $rb (on $back). Set RELEASE_BRANCH or checkout $rb to enable."
    return 0
  fi
  git -C "$ROOT" fetch origin "$rb" "$mb" || {
    echo "ERROR: git fetch origin $rb $mb failed." >&2
    return 1
  }
  if ! git -C "$ROOT" show-ref --verify --quiet "refs/remotes/origin/$mb"; then
    echo "origin/$mb not found; skipping merge to main."
    return 0
  fi

  if [[ "${MERGE_TO_MAIN_VIA_PR:-}" == "1" ]]; then
    echo "MERGE_TO_MAIN_VIA_PR=1: opening PR ${rb} -> ${mb} (skipping direct push to protected main)."
    git -C "$ROOT" checkout "$rb" || true
    create_pr_release_to_main
    return $?
  fi

  if git -C "$ROOT" show-ref --verify --quiet "refs/heads/$mb"; then
    git -C "$ROOT" checkout "$mb" || return 1
  else
    git -C "$ROOT" checkout -b "$mb" "origin/$mb" || return 1
  fi
  if ! git -C "$ROOT" pull --no-edit origin "$mb"; then
    echo "ERROR: could not update local $mb from origin." >&2
    git -C "$ROOT" checkout "$rb" || true
    return 1
  fi
  if ! git -C "$ROOT" merge --no-ff "$rb" -m "Merge branch '${rb}' into ${mb} (Release v${VERSION})"; then
    echo "ERROR: merge conflict merging $rb into $mb. Resolve on $mb, push, then: git checkout $rb" >&2
    return 1
  fi
  if git -C "$ROOT" push origin "$mb"; then
    git -C "$ROOT" checkout "$rb" || true
    echo "Merged $rb into $mb and pushed. GitHub default branch should match the release line."
    return 0
  fi

  echo "WARN: push to origin/$mb failed (branch protection?). Resetting local $mb to match remote and opening a PR." >&2
  git -C "$ROOT" reset --hard "origin/$mb"
  git -C "$ROOT" checkout "$rb" || true
  if create_pr_release_to_main; then
    echo "Open the PR on GitHub and merge when allowed by repository rules."
    return 0
  fi
  return 1
}

post_push_merge_to_main_prompt() {
  local back="$1"
  local rb="${RELEASE_BRANCH:-dev}"
  local mb="${MAIN_BRANCH:-main}"
  if [[ "$back" != "$rb" ]]; then
    return 0
  fi
  if [[ "$MERGE_RELEASE_TO_MAIN" == "1" ]]; then
    maybe_merge_release_into_main "$back" || true
    return
  fi
  local yn
  yn="$(read_tty "Merge ${rb} into ${mb} and push? (sync default branch with release) [Y/n] ")"
  if [[ -z "$yn" ]] || [[ "$yn" =~ ^[yY] ]]; then
    maybe_merge_release_into_main "$back" || true
  fi
}

# Next patch after first plain semver release heading (top of history below [Unreleased]).
suggest_next_patch_version() {
  local line ma mi pa
  line="$(grep -m1 -E '^## \[v[0-9]+\.[0-9]+\.[0-9]+\]' "$CHANGELOG" || true)"
  [[ -n "$line" ]] || return 1
  if [[ "$line" =~ ^##\ \[v([0-9]+)\.([0-9]+)\.([0-9]+)\] ]]; then
    ma="${BASH_REMATCH[1]}"
    mi="${BASH_REMATCH[2]}"
    pa="${BASH_REMATCH[3]}"
    echo "${ma}.${mi}.$((pa + 1))"
    return 0
  fi
  return 1
}

if [[ -z "$VERSION_RAW" ]] && [[ "$PUBLISH_ONLY" -eq 0 ]]; then
  SUGGEST=""
  if ! SUGGEST="$(suggest_next_patch_version)"; then
    echo "ERROR: Could not infer next version: no ## [vM.m.p] heading found in $CHANGELOG" >&2
    exit 1
  fi
  if [[ -r /dev/tty ]]; then
    VERSION_RAW="$(read_tty "Next version [${SUGGEST}]: ")"
    VERSION_RAW="${VERSION_RAW:-$SUGGEST}"
  else
    VERSION_RAW="$SUGGEST"
  fi
fi

VERSION_RAW="${VERSION_RAW#"${VERSION_RAW%%[![:space:]]*}"}"
VERSION_RAW="${VERSION_RAW%"${VERSION_RAW##*[![:space:]]}"}"

VERSION="${VERSION_RAW#v}"
if ! [[ "$VERSION" =~ ^[0-9]+\.[0-9]+\.[0-9]+(-[a-zA-Z0-9.]+)?$ ]]; then
  echo "Version must look like 1.2.10 or 1.2.10-rc1 (got: $VERSION_RAW)" >&2
  exit 1
fi

if [[ "$PUBLISH_ONLY" -eq 1 ]]; then
  if ! grep -qE "^## \\[v${VERSION//./\\.}\\]" "$CHANGELOG"; then
    echo "ERROR: No ## [v$VERSION] section in $CHANGELOG (needed for release notes)." >&2
    exit 1
  fi
  if [[ -f "$DOCKER_CONFIG" ]] && grep -q '^VERSION=' "$DOCKER_CONFIG"; then
    sed -i 's/^VERSION=.*/VERSION=v'"${VERSION}"'/' "$DOCKER_CONFIG"
    echo "Updated $DOCKER_CONFIG: VERSION=v${VERSION}"
  fi
  echo ""
  echo "Post-release: GitHub (gh) + Docker. Tag v${VERSION} should already exist on the remote."
  pub_br="$(git -C "$ROOT" rev-parse --abbrev-ref HEAD 2>/dev/null || echo "")"
  if [[ -r /dev/tty ]]; then
    post_push_gh_prompt
    post_push_docker_prompt
    post_push_merge_to_main_prompt "$pub_br"
  else
    echo "No /dev/tty: set CREATE_GH_RELEASE=1 and/or PUBLISH_DOCKER=1 and/or MERGE_RELEASE_TO_MAIN=1."
    if [[ "$CREATE_GH_RELEASE" == "1" ]]; then
      maybe_gh_release || true
    fi
    if [[ "$PUBLISH_DOCKER" == "1" ]]; then
      maybe_docker_push || true
    fi
    if [[ "$MERGE_RELEASE_TO_MAIN" == "1" ]]; then
      maybe_merge_release_into_main "$pub_br" || true
    fi
  fi
  exit 0
fi

if grep -qE "^## \\[v${VERSION//./\\.}\\]" "$CHANGELOG"; then
  echo "ERROR: $CHANGELOG already contains ## [v$VERSION]. Remove it or pick another version." >&2
  exit 1
fi

RELEASE_DATE="$(date +%Y-%m-%d)"

python3 - "$CHANGELOG" "$VERSION" "$RELEASE_DATE" "$DRY_RUN" <<'PY'
import pathlib
import re
import sys

cl_path = pathlib.Path(sys.argv[1])
version = sys.argv[2]
release_date = sys.argv[3]
dry = sys.argv[4] == "1"

text = cl_path.read_text(encoding="utf-8")
# Section breaks are a blank line before/after --- (Keep a Changelog style).
sep = "\n\n---\n\n"
parts = text.split(sep)

idx = None
for i, block in enumerate(parts):
    if block.lstrip().startswith("## [Unreleased]"):
        idx = i
        break

if idx is None:
    print("ERROR: No ## [Unreleased] section found (expected after a --- separator).", file=sys.stderr)
    sys.exit(1)

old = parts[idx].strip()
if "### Major Features" not in old:
    print("ERROR: [Unreleased] must contain ### Major Features.", file=sys.stderr)
    sys.exit(1)

# Body under [Unreleased]: drop the first line (heading)
lines = old.split("\n", 1)
if not lines[0].startswith("## [Unreleased]"):
    print("ERROR: Malformed [Unreleased] block.", file=sys.stderr)
    sys.exit(1)
rest = lines[1].lstrip("\n") if len(lines) > 1 else ""

new_unreleased = """## [Unreleased]

### Major Features

_Add entries here during development; rotate into a dated release section when tagging._"""

new_release = f"## [v{version}] ({release_date})\n{rest}"

parts[idx] = new_unreleased.strip()
parts.insert(idx + 1, new_release.strip())

out = sep.join(parts)

if dry:
    print(out)
    print("\n--- dry-run: file not written ---", file=sys.stderr)
else:
    cl_path.write_text(out, encoding="utf-8")
    print(f"Updated {cl_path}: [Unreleased] -> [v{version}] ({release_date})")
PY

if [[ "$DRY_RUN" -eq 1 ]]; then
  exit 0
fi

if [[ -f "$DOCKER_CONFIG" ]] && grep -q '^VERSION=' "$DOCKER_CONFIG"; then
  sed -i 's/^VERSION=.*/VERSION=v'"${VERSION}"'/' "$DOCKER_CONFIG"
  echo "Updated $DOCKER_CONFIG: VERSION=v${VERSION}"
else
  echo "WARN: $DOCKER_CONFIG missing or has no VERSION= line; skipping Docker config bump." >&2
fi

echo ""
echo "Next steps:"
echo "  git add CHANGELOG.md docker-image.config && git commit -m \"Release v${VERSION}\""
echo "  git tag -a \"v${VERSION}\" -m \"v${VERSION}\""
echo "  git push && git push origin \"v${VERSION}\""
echo "  (CI: .github/workflows creates the GitHub Release and publishes Docker images on tag push.)"
echo "  Then merge ${RELEASE_BRANCH:-dev} into ${MAIN_BRANCH:-main} so the default branch matches the release."
echo ""

if [[ -r /dev/tty ]]; then
  ans="$(read_tty "Create git commit and annotated tag v${VERSION} now? [y/N] ")"
  case "${ans}" in
    y|Y|yes|YES)
      git -C "$ROOT" add CHANGELOG.md
      [[ -f "$DOCKER_CONFIG" ]] && git -C "$ROOT" add docker-image.config
      git -C "$ROOT" commit -m "Release v${VERSION}"
      git -C "$ROOT" tag -a "v${VERSION}" -m "v${VERSION}"
      echo "Created commit and tag v${VERSION}."
      PUSHED=0
      ans2="$(read_tty "Push branch and tag to origin? [y/N] ")"
      case "${ans2}" in
        y|Y|yes|YES)
          br="$(git -C "$ROOT" rev-parse --abbrev-ref HEAD)"
          git -C "$ROOT" push origin "$br" && git -C "$ROOT" push origin "v${VERSION}"
          PUSHED=1
          ;;
        *) echo "Skipped push." ;;
      esac

      if [[ "$PUSHED" -eq 1 ]]; then
        echo ""
        post_push_gh_prompt
        post_push_docker_prompt
        post_push_merge_to_main_prompt "$br"
      fi
      ;;
    *) echo "Skipped commit/tag." ;;
  esac
else
  echo "No /dev/tty: re-run in a real terminal for prompts, or use git/gh/docker manually."
  echo "To finish a pushed tag:  $(basename "$0") ${VERSION} --publish-only"
fi
