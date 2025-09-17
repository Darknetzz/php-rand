#!/usr/bin/env bash

# from the repo root
docker login

if [[ ! -f .env ]]; then
  echo ".env file not found! Please create one with IMAGE and VERSION variables."
  exit 1
fi

source .env

if [[ -z "$IMAGE" || -z "$TAG" || -z "$VERSION" ]]; then
  echo "IMAGE, TAG, and VERSION variables must be set in the .env file."
  exit 1
fi

docker build -t $IMAGE:$TAG -f Dockerfile .
docker tag $IMAGE:$TAG $IMAGE:$VERSION
docker push $IMAGE:$TAG
docker push $IMAGE:$VERSION