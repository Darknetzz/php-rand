#!/usr/bin/env bash
# Legacy helper: Docker publish moved to GitLab CI (.gitlab-ci.yml).
# Clears a previous core.hooksPath=.githooks from older clones.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

current="$(git config --get core.hooksPath || true)"
if [[ "$current" == ".githooks" || "$current" == "$ROOT/.githooks" ]]; then
  git config --unset core.hooksPath
  echo "Cleared core.hooksPath (.githooks / local Docker pre-push retired)."
else
  echo "No .githooks core.hooksPath set (nothing to clear)."
fi

echo "Docker images publish via GitLab CI on gitlab01 (see .gitlab-ci.yml)."
echo "  :dev/:develop  → push branch 'dev'"
echo "  :latest/:vX.Y.Z → push a version tag (vX.Y.Z)"
echo "Local fallback: ./docker-pushimage.sh --dev | --release"
