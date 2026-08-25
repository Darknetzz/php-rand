#!/usr/bin/env bash

# Local Docker publish workflow (replaces GitHub Actions docker-dev / docker-release).
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

set -euo pipefail

SCRIPT_DIR=$(cd "$(dirname "$0")" && pwd)
cd "$SCRIPT_DIR"

MODE="release"
VERSION_ARG=""
DRY_RUN=0

usage() {
  cat <<'EOF'
Local Docker image build + push (no GitHub Actions).

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

if [[ -z "${GITHUB_TOKEN:-}" ]] && command -v gh &>/dev/null; then
  GITHUB_TOKEN="$(gh auth token 2>/dev/null || true)"
fi

if [[ -z "${IMAGE:-}" ]]; then
  echo "IMAGE must be set in docker-image.config."
  exit 1
fi

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

echo "=== Local Docker publish ($MODE) ==="
echo "Image:            $IMAGE"
echo "Build version:    $BUILD_VERSION (PHP_RAND_VERSION)"
echo "Tags:             ${TAGS[*]}"
if [[ -n "${GHCR_IMAGE:-}" ]]; then
  echo "GHCR image:       $GHCR_IMAGE"
fi
echo "Docker Hub:       $([[ "$SKIP_DOCKERHUB" == "1" ]] && echo skip || echo push)"
echo "GHCR:             $([[ "$SKIP_GHCR" == "1" || -z "${GHCR_IMAGE:-}" || -z "${GITHUB_TOKEN:-}" ]] && echo skip || echo push)"

if [[ "$DRY_RUN" -eq 1 ]]; then
  echo
  echo "[dry-run] docker build --build-arg PHP_RAND_VERSION=$BUILD_VERSION -t $IMAGE:$PRIMARY_TAG -f Dockerfile ."
  for t in "${TAGS[@]}"; do
    [[ "$t" == "$PRIMARY_TAG" ]] && continue
    echo "[dry-run] docker tag $IMAGE:$PRIMARY_TAG $IMAGE:$t"
  done
  if [[ "$SKIP_DOCKERHUB" != "1" ]]; then
    for t in "${TAGS[@]}"; do
      echo "[dry-run] docker push $IMAGE:$t"
    done
  fi
  if [[ "$SKIP_GHCR" != "1" && -n "${GHCR_IMAGE:-}" && -n "${GITHUB_TOKEN:-}" ]]; then
    for t in "${TAGS[@]}"; do
      echo "[dry-run] docker tag $IMAGE:$PRIMARY_TAG $GHCR_IMAGE:$t"
      echo "[dry-run] docker push $GHCR_IMAGE:$t"
    done
  fi
  echo "[dry-run] No build or push performed."
  exit 0
fi

echo "=== Building $IMAGE:$PRIMARY_TAG (PHP_RAND_VERSION=$BUILD_VERSION) ==="
docker build --build-arg PHP_RAND_VERSION="$BUILD_VERSION" -t "$IMAGE:$PRIMARY_TAG" -f Dockerfile .
for t in "${TAGS[@]}"; do
  if [[ "$t" != "$PRIMARY_TAG" ]]; then
    docker tag "$IMAGE:$PRIMARY_TAG" "$IMAGE:$t"
  fi
done

if [[ "$SKIP_DOCKERHUB" != "1" ]]; then
  echo "=== Docker Hub login ==="
  if [[ -n "${DOCKERHUB_USERNAME:-}" && -n "${DOCKERHUB_TOKEN:-}" ]]; then
    echo "$DOCKERHUB_TOKEN" | docker login -u "$DOCKERHUB_USERNAME" --password-stdin
  else
    docker login
  fi
  echo "=== Pushing to Docker Hub ==="
  for t in "${TAGS[@]}"; do
    docker push "$IMAGE:$t"
  done
else
  echo "Skipping Docker Hub (SKIP_DOCKERHUB=1)."
fi

if [[ "$SKIP_GHCR" == "1" ]]; then
  echo "Skipping GHCR (SKIP_GHCR=1)."
elif [[ -n "${GHCR_IMAGE:-}" && -n "${GITHUB_TOKEN:-}" ]]; then
  echo "=== Pushing to GitHub Container Registry (ghcr.io) ==="
  GHCR_OWNER=$(echo "$GHCR_IMAGE" | cut -d/ -f2)
  echo "$GITHUB_TOKEN" | docker login ghcr.io -u "$GHCR_OWNER" --password-stdin
  PUSHED=()
  for t in "${TAGS[@]}"; do
    docker tag "$IMAGE:$PRIMARY_TAG" "$GHCR_IMAGE:$t"
    docker push "$GHCR_IMAGE:$t"
    PUSHED+=("$GHCR_IMAGE:$t")
  done
  echo "Pushed ${PUSHED[*]}"
else
  echo "Skipping GHCR (set GITHUB_TOKEN in .env/.env.local, or run: gh auth refresh -s write:packages)."
fi

echo "=== Done ($MODE) ==="
echo "Pulled tags to refresh a host (e.g. docker02 demo):"
echo "  docker pull $IMAGE:$PRIMARY_TAG"
if [[ "$MODE" == "dev" ]]; then
  echo "  # then recreate the compose/stack service that uses $IMAGE:dev"
fi
