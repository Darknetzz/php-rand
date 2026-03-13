#!/usr/bin/env bash

# This file is only intended to help automate the docker image build and push process.
# Make sure you have a .env file in the root of the repo with the following variables:
#   IMAGE=your-dockerhub-username/php-rand
#   TAG=latest
#   VERSION=v1.2.4   <- set to the actual release version; "latest" will point to this
# Optional (GitHub Container Registry):
#   GHCR_IMAGE=ghcr.io/owner/php-rand
#   GITHUB_TOKEN=ghp_xxx   (PAT with write:packages; do not commit)

# from the repo root
set -e

if [[ ! -f .env ]]; then
  echo ".env file not found! Please create one with IMAGE, TAG, and VERSION."
  exit 1
fi

source .env

if [[ -z "$IMAGE" || -z "$TAG" || -z "$VERSION" ]]; then
  echo "IMAGE, TAG, and VERSION variables must be set in the .env file."
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
  echo "Skipping GHCR (set GHCR_IMAGE and GITHUB_TOKEN in .env to push to ghcr.io)."
fi