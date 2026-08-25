#!/usr/bin/env bash
# Point this clone at .githooks/ (tracked). Safe to re-run.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

if [[ ! -d .githooks ]]; then
  echo "Missing .githooks/ in $ROOT" >&2
  exit 1
fi

chmod +x .githooks/* 2>/dev/null || true
git config core.hooksPath .githooks
echo "Installed git hooks: core.hooksPath=.githooks"
echo "  pre-push → ./docker-pushimage.sh --dev when pushing branch 'dev'"
echo "  Skip:     SKIP_DOCKER_PUBLISH=1 git push"
