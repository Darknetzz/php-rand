#!/usr/bin/env bash

# Build and push Docker image. Config (IMAGE, TAG, VERSION, GHCR_IMAGE) is in
# docker-image.config (tracked). Secrets (GITHUB_TOKEN) go in .env (not tracked).

# from the repo root
set -e

SCRIPT_DIR=$(cd "$(dirname "$0")" && pwd)
cd "$SCRIPT_DIR"

if [[ ! -f docker-image.config ]]; then
  echo "docker-image.config not found."
  exit 1
fi
source docker-image.config

[[ -f .env ]] && source .env

if [[ -z "$IMAGE" || -z "$TAG" || -z "$VERSION" ]]; then
  echo "IMAGE, TAG, and VERSION must be set in docker-image.config."
  exit 1
fi

echo "=== Docker Hub login ==="
docker login

docker build -t $IMAGE:$TAG -f Dockerfile .
docker tag $IMAGE:$TAG $IMAGE:$VERSION
docker push $IMAGE:$TAG
docker push $IMAGE:$VERSION

if [[ -n "$GHCR_IMAGE" && -n "$GITHUB_TOKEN" ]]; then
  echo "=== Pushing to GitHub Container Registry (ghcr.io) ==="
  GHCR_OWNER=$(echo "$GHCR_IMAGE" | cut -d/ -f2)
  echo "$GITHUB_TOKEN" | docker login ghcr.io -u "$GHCR_OWNER" --password-stdin
  docker tag $IMAGE:$TAG $GHCR_IMAGE:$TAG
  docker tag $IMAGE:$VERSION $GHCR_IMAGE:$VERSION
  docker push $GHCR_IMAGE:$TAG
  docker push $GHCR_IMAGE:$VERSION
  echo "Pushed $GHCR_IMAGE:$TAG and $GHCR_IMAGE:$VERSION"
else
  echo "Skipping GHCR (set GITHUB_TOKEN in .env to push to ghcr.io)."
fi