#!/usr/bin/env bash

# This file is only intended to help automate the docker image build and push process.
# Make sure you have a .env file in the root of the repo with the following variables:
# IMAGE=your-dockerhub-username/php-rand
# TAG=latest
# VERSION=v1.0.0

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