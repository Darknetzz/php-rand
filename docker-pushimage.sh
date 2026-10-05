#!/usr/bin/env bash

# Docker publish workflow (GitLab CI primary; local fallback).
# Config (IMAGE, TAG, VERSION, GHCR_IMAGE) lives in docker-image.config (tracked).
# Secrets (DOCKERHUB_*, GITHUB_TOKEN) go in .env or .env.local (not tracked).
#
# Usage:
#   ./docker-pushimage.sh --dev                 # rolling :dev + :develop (demo / tip of branch)
#   ./docker-pushimage.sh                       # release from docker-image.config (:latest + :vX.Y.Z)
#   ./docker-pushimage.sh --release v1.4.1      # release with VERSION override
#   ./docker-pushimage.sh --dev --dry-run
#   SKIP_DOCKERHUB=1 ./docker-pushimage.sh --dev
#   PUBLISH_DOCKER=1 ./scripts/release.sh 1.4.0 --publish-only
#
# Sync guarantee: one build is tagged for every enabled registry, then pushed.
# If a registry is enabled (not SKIP_* and credentials present), login/push
# failure aborts the script (non-zero). Partial Hub-only success is not OK
# when GHCR was supposed to publish.

set -euo pipefail

SCRIPT_DIR=$(cd "$(dirname "$0")" && pwd)
cd "$SCRIPT_DIR"

MODE="release"
VERSION_ARG=""
DRY_RUN=0

usage() {
  cat <<'EOF'
Docker image build + push (GitLab CI / local).

Usage:
  ./docker-pushimage.sh [--dev | --release [vX.Y.Z]] [--dry-run] [-h|--help]

Modes:
  --dev              Build PHP_RAND_VERSION=dev; push IMAGE:dev and IMAGE:develop
                     (and the same tags on GHCR when configured).
  --release [ver]    Build from docker-image.config (default). Optional ver overrides
                     VERSION (e.g. v1.4.1 or 1.4.1). Tags: TAG (usually latest),
                     VERSION, and stripped VERSION (1.4.1).

Options:
  --dry-run          Print planned build/tags/pushes; do not build or push.
  -h, --help         Show this help.

Env:
  SKIP_DOCKERHUB=1   Skip Docker Hub login/push (GHCR only if token available).
  SKIP_GHCR=1        Skip GHCR push even if GITHUB_TOKEN / gh auth is available.
  IMAGE_OVERRIDE, TAG_OVERRIDE, VERSION_OVERRIDE, GHCR_IMAGE_OVERRIDE
  DOCKERHUB_USERNAME / DOCKERHUB_TOKEN  Non-interactive Hub login (.env / .env.local)
  GITHUB_TOKEN       GHCR push (.env / .env.local, or: gh auth token)
  GITLAB_CI / CI     When set, missing credentials for a non-skipped registry is fatal
                     (same as .gitlab-ci.yml before_script checks).

Examples:
  ./docker-pushimage.sh --dev
  ./docker-pushimage.sh --release
  ./docker-pushimage.sh --release 1.4.1
  SKIP_DOCKERHUB=1 ./docker-pushimage.sh --dev --dry-run
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --dev)
      MODE="dev"
      shift
      ;;
    --release)
      MODE="release"
      shift
      if [[ $# -gt 0 && "$1" != --* ]]; then
        VERSION_ARG="$1"
        shift
      fi
      ;;
    --dry-run)
      DRY_RUN=1
      shift
      ;;
    -h|--help)
      usage
      exit 0
      ;;
    *)
      echo "Unknown argument: $1" >&2
      usage >&2
      exit 1
      ;;
  esac
done

if ! docker info &>/dev/null; then
  echo "Cannot connect to the Docker daemon (e.g. permission denied on docker.sock)."
  echo "Start Docker, then run this script again."
  exit 1
fi

if [[ ! -f docker-image.config ]]; then
  echo "docker-image.config not found."
  exit 1
fi
# shellcheck source=/dev/null
source docker-image.config

IMAGE="${IMAGE_OVERRIDE:-$IMAGE}"
TAG="${TAG_OVERRIDE:-$TAG}"
VERSION="${VERSION_OVERRIDE:-$VERSION}"
GHCR_IMAGE="${GHCR_IMAGE_OVERRIDE:-$GHCR_IMAGE}"
SKIP_DOCKERHUB="${SKIP_DOCKERHUB:-}"
SKIP_GHCR="${SKIP_GHCR:-}"

if [[ -n "$VERSION_ARG" ]]; then
  if [[ "$VERSION_ARG" == v* ]]; then
    VERSION="$VERSION_ARG"
  else
    VERSION="v${VERSION_ARG}"
  fi
fi

# Prefer .env.local (survives pulls/merges); fallback to .env
load_env_file() {
  local f="$1"
  [[ -f "$f" ]] || return 0
  set -a
  # shellcheck source=/dev/null
  source "$f"
  set +a
}
load_env_file .env.local
load_env_file .env

SKIP_DOCKERHUB="${SKIP_DOCKERHUB:-}"
SKIP_GHCR="${SKIP_GHCR:-}"

# Prefer .env token when set; otherwise use gh. Always fetch gh via env -u so a
# stale exported GITHUB_TOKEN/GH_TOKEN cannot poison `gh auth token`.
gh_auth_token() {
  if ! command -v gh &>/dev/null; then
    return 0
  fi
  env -u GITHUB_TOKEN -u GH_TOKEN gh auth token 2>/dev/null || true
}

if [[ -z "${GITHUB_TOKEN:-}" ]]; then
  GITHUB_TOKEN="$(gh_auth_token)"
fi

if [[ -z "${IMAGE:-}" ]]; then
  echo "IMAGE must be set in docker-image.config."
  exit 1
fi

in_ci() {
  [[ "${GITLAB_CI:-}" == "true" || "${CI:-}" == "true" ]]
}

BUILD_VERSION=""
TAGS=()

if [[ "$MODE" == "dev" ]]; then
  BUILD_VERSION="dev"
  TAGS=("dev" "develop")
else
  if [[ -z "${TAG:-}" || -z "${VERSION:-}" ]]; then
    echo "TAG and VERSION must be set in docker-image.config for --release."
    exit 1
  fi
  BUILD_VERSION="$VERSION"
  STRIPPED_TAG="${VERSION#v}"
  TAGS=("$TAG" "$VERSION")
  if [[ "$VERSION" != "$STRIPPED_TAG" ]]; then
    TAGS+=("$STRIPPED_TAG")
  fi
fi

# Deduplicate tags while preserving order
UNIQUE_TAGS=()
for t in "${TAGS[@]}"; do
  seen=0
  for u in "${UNIQUE_TAGS[@]:-}"; do
    [[ "$u" == "$t" ]] && seen=1 && break
  done
  [[ $seen -eq 0 ]] && UNIQUE_TAGS+=("$t")
done
TAGS=("${UNIQUE_TAGS[@]}")

PRIMARY_TAG="${TAGS[0]}"

PUSH_HUB=0
PUSH_GHCR=0
if [[ "$SKIP_DOCKERHUB" != "1" ]]; then
  PUSH_HUB=1
fi
if [[ "$SKIP_GHCR" != "1" && -n "${GHCR_IMAGE:-}" ]]; then
  if [[ -n "${GITHUB_TOKEN:-}" ]]; then
    PUSH_GHCR=1
  elif in_ci; then
    echo "GITHUB_TOKEN required for GHCR in CI (or set SKIP_GHCR=1)." >&2
    exit 1
  fi
fi

if [[ "$PUSH_HUB" -eq 0 && "$PUSH_GHCR" -eq 0 ]]; then
  echo "Nothing to publish: both Docker Hub and GHCR are skipped or unconfigured." >&2
  exit 1
fi

if in_ci && [[ "$PUSH_HUB" -eq 1 ]]; then
  if [[ -z "${DOCKERHUB_USERNAME:-}" || -z "${DOCKERHUB_TOKEN:-}" ]]; then
    echo "DOCKERHUB_USERNAME + DOCKERHUB_TOKEN required in CI (or set SKIP_DOCKERHUB=1)." >&2
    exit 1
  fi
fi

echo "=== Docker publish ($MODE) ==="
echo "Image:            $IMAGE"
echo "Build version:    $BUILD_VERSION (PHP_RAND_VERSION)"
echo "Tags:             ${TAGS[*]}"
if [[ -n "${GHCR_IMAGE:-}" ]]; then
  echo "GHCR image:       $GHCR_IMAGE"
fi
echo "Docker Hub:       $([[ "$PUSH_HUB" -eq 1 ]] && echo push || echo skip)"
echo "GHCR:             $([[ "$PUSH_GHCR" -eq 1 ]] && echo push || echo skip)"

dest_refs=()
for t in "${TAGS[@]}"; do
  [[ "$PUSH_HUB" -eq 1 ]] && dest_refs+=("$IMAGE:$t")
  [[ "$PUSH_GHCR" -eq 1 ]] && dest_refs+=("$GHCR_IMAGE:$t")
done

if [[ "$DRY_RUN" -eq 1 ]]; then
  echo
  if docker buildx version &>/dev/null; then
    echo "[dry-run] docker buildx build --build-arg PHP_RAND_VERSION=$BUILD_VERSION \\"
    for ref in "${dest_refs[@]}"; do
      echo "[dry-run]   -t $ref \\"
    done
    echo "[dry-run]   --push -f Dockerfile ."
  else
    echo "[dry-run] docker build --build-arg PHP_RAND_VERSION=$BUILD_VERSION -t $IMAGE:$PRIMARY_TAG -f Dockerfile ."
    for ref in "${dest_refs[@]}"; do
      [[ "$ref" == "$IMAGE:$PRIMARY_TAG" ]] && continue
      echo "[dry-run] docker tag $IMAGE:$PRIMARY_TAG $ref"
    done
    for ref in "${dest_refs[@]}"; do
      echo "[dry-run] docker push $ref"
    done
  fi
  echo "[dry-run] No build or push performed."
  exit 0
fi

login_hub() {
  echo "=== Docker Hub login ==="
  if [[ -n "${DOCKERHUB_USERNAME:-}" && -n "${DOCKERHUB_TOKEN:-}" ]]; then
    echo "$DOCKERHUB_TOKEN" | docker login -u "$DOCKERHUB_USERNAME" --password-stdin
  else
    # Existing creds store; never read a password from inherited stdin (e.g. git hooks).
    docker login </dev/null
  fi
}

login_ghcr() {
  echo "=== GHCR login ==="
  local ghcr_owner ghcr_user gh_live
  ghcr_owner=$(echo "$GHCR_IMAGE" | cut -d/ -f2)
  ghcr_user="$ghcr_owner"
  if command -v gh &>/dev/null; then
    ghcr_user="$(env -u GITHUB_TOKEN -u GH_TOKEN gh api user -q .login 2>/dev/null || echo "$ghcr_owner")"
  fi
  if echo "$GITHUB_TOKEN" | docker login ghcr.io -u "$ghcr_user" --password-stdin; then
    return 0
  fi
  # Stale GITHUB_TOKEN in .env.local is a common failure mode; retry with gh.
  gh_live="$(gh_auth_token)"
  if [[ -n "$gh_live" && "$gh_live" != "$GITHUB_TOKEN" ]]; then
    echo "GHCR login with GITHUB_TOKEN failed; retrying with gh auth token…" >&2
    if echo "$gh_live" | docker login ghcr.io -u "$ghcr_user" --password-stdin; then
      GITHUB_TOKEN="$gh_live"
      return 0
    fi
  fi
  echo "GHCR login failed. Fix with: gh auth refresh -s write:packages (or update GITHUB_TOKEN)." >&2
  return 1
}

if [[ "$PUSH_HUB" -eq 1 ]]; then
  login_hub
fi
if [[ "$PUSH_GHCR" -eq 1 ]]; then
  login_ghcr
fi

# Prefer buildx multi-destination --push so Hub + GHCR get the same build in one go.
use_buildx=0
if docker buildx version &>/dev/null; then
  use_buildx=1
fi

if [[ "$use_buildx" -eq 1 ]]; then
  echo "=== buildx build + push (all registries) ==="
  # Default docker driver is enough for single-arch --push; create a named
  # builder only when no builder is usable yet (common in fresh dind).
  if ! docker buildx inspect >/dev/null 2>&1; then
    docker buildx create --name php-rand-publisher --use >/dev/null
  fi
  build_args=(
    buildx build
    --build-arg "PHP_RAND_VERSION=$BUILD_VERSION"
    --provenance=false
    --sbom=false
  )
  for ref in "${dest_refs[@]}"; do
    build_args+=(-t "$ref")
  done
  build_args+=(--push -f Dockerfile .)
  docker "${build_args[@]}"
else
  echo "=== Building $IMAGE:$PRIMARY_TAG (PHP_RAND_VERSION=$BUILD_VERSION) ==="
  docker build --build-arg PHP_RAND_VERSION="$BUILD_VERSION" -t "$IMAGE:$PRIMARY_TAG" -f Dockerfile .
  for ref in "${dest_refs[@]}"; do
    if [[ "$ref" != "$IMAGE:$PRIMARY_TAG" ]]; then
      docker tag "$IMAGE:$PRIMARY_TAG" "$ref"
    fi
  done
  echo "=== Pushing ==="
  for ref in "${dest_refs[@]}"; do
    docker push "$ref"
  done
fi

echo "=== Done ($MODE) ==="
echo "Published:"
for ref in "${dest_refs[@]}"; do
  echo "  $ref"
done
echo "Pulled tags to refresh a host (e.g. docker02 demo):"
echo "  docker pull $IMAGE:$PRIMARY_TAG"
if [[ "$MODE" == "dev" ]]; then
  echo "  # then recreate the compose/stack service that uses $IMAGE:dev"
fi
