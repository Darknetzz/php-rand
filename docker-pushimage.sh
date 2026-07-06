#!/usr/bin/env bash

# Build and push Docker image. Config (IMAGE, TAG, VERSION, GHCR_IMAGE) is in
# docker-image.config (tracked). Secrets (GITHUB_TOKEN) go in .env or .env.local (not tracked).
# With GitHub Actions disabled, run locally:
#   SKIP_DOCKERHUB=1 ./docker-pushimage.sh
# or:
#   PUBLISH_DOCKER=1 ./scripts/release.sh 1.4.0 --publish-only

set -e

SCRIPT_DIR=$(cd "$(dirname "$0")" && pwd)
cd "$SCRIPT_DIR"

if ! docker info &>/dev/null; then
  echo "Cannot connect to the Docker daemon (e.g. permission denied on docker.sock)."
  echo "Start Docker Desktop, then run this script again."
  exit 1
fi

if [[ ! -f docker-image.config ]]; then
  echo "docker-image.config not found."
  exit 1
fi
source docker-image.config

# Optional overrides for release automation
IMAGE="${IMAGE_OVERRIDE:-$IMAGE}"
TAG="${TAG_OVERRIDE:-$TAG}"
VERSION="${VERSION_OVERRIDE:-$VERSION}"
GHCR_IMAGE="${GHCR_IMAGE_OVERRIDE:-$GHCR_IMAGE}"
SKIP_DOCKERHUB="${SKIP_DOCKERHUB:-}"

# Prefer .env.local (never in repo history, so survives pulls/merges); fallback to .env
[[ -f .env.local ]] && source .env.local
[[ -f .env ]] && source .env

# Fallback: gh CLI token (needs write:packages for GHCR push)
if [[ -z "${GITHUB_TOKEN:-}" ]] && command -v gh &>/dev/null; then
  GITHUB_TOKEN="$(gh auth token 2>/dev/null || true)"
fi

if [[ -z "$IMAGE" || -z "$TAG" || -z "$VERSION" ]]; then
  echo "IMAGE, TAG, and VERSION must be set in docker-image.config."
  exit 1
fi

STRIPPED_TAG="${VERSION#v}"

echo "=== Building $IMAGE:$TAG (PHP_RAND_VERSION=$VERSION) ==="
docker build --build-arg PHP_RAND_VERSION="$VERSION" -t "$IMAGE:$TAG" -f Dockerfile .
docker tag "$IMAGE:$TAG" "$IMAGE:$VERSION"
if [[ "$VERSION" != "$STRIPPED_TAG" ]]; then
  docker tag "$IMAGE:$TAG" "$IMAGE:$STRIPPED_TAG"
fi

if [[ "$SKIP_DOCKERHUB" != "1" ]]; then
  echo "=== Docker Hub login ==="
  if [[ -n "${DOCKERHUB_USERNAME:-}" && -n "${DOCKERHUB_TOKEN:-}" ]]; then
    echo "$DOCKERHUB_TOKEN" | docker login -u "$DOCKERHUB_USERNAME" --password-stdin
  else
    docker login
  fi
  echo "=== Pushing to Docker Hub ==="
  docker push "$IMAGE:$TAG"
  docker push "$IMAGE:$VERSION"
  if [[ "$VERSION" != "$STRIPPED_TAG" ]]; then
    docker push "$IMAGE:$STRIPPED_TAG"
  fi
else
  echo "Skipping Docker Hub (SKIP_DOCKERHUB=1)."
fi

if [[ -n "$GHCR_IMAGE" && -n "${GITHUB_TOKEN:-}" ]]; then
  echo "=== Pushing to GitHub Container Registry (ghcr.io) ==="
  GHCR_OWNER=$(echo "$GHCR_IMAGE" | cut -d/ -f2)
  echo "$GITHUB_TOKEN" | docker login ghcr.io -u "$GHCR_OWNER" --password-stdin
  docker tag "$IMAGE:$TAG" "$GHCR_IMAGE:$TAG"
  docker tag "$IMAGE:$VERSION" "$GHCR_IMAGE:$VERSION"
  if [[ "$VERSION" != "$STRIPPED_TAG" ]]; then
    docker tag "$IMAGE:$TAG" "$GHCR_IMAGE:$STRIPPED_TAG"
  fi
  docker push "$GHCR_IMAGE:$TAG"
  docker push "$GHCR_IMAGE:$VERSION"
  if [[ "$VERSION" != "$STRIPPED_TAG" ]]; then
    docker push "$GHCR_IMAGE:$STRIPPED_TAG"
    echo "Pushed $GHCR_IMAGE:$TAG, $GHCR_IMAGE:$VERSION, and $GHCR_IMAGE:$STRIPPED_TAG"
  else
    echo "Pushed $GHCR_IMAGE:$TAG and $GHCR_IMAGE:$VERSION"
  fi
else
  echo "Skipping GHCR (set GITHUB_TOKEN in .env/.env.local, or run: gh auth refresh -s write:packages)."
fi
