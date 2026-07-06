#!/usr/bin/env bash
set -euo pipefail

DRY_RUN=0
CHANGELOG_FILE=""
SECTION_TITLE=""

print_help() {
  cat <<'EOF'
Extract a changelog section by title.

Usage:
  ./scripts/extract_changelog_section.sh <changelog-file> <section-title> [options]

Example:
  ./scripts/extract_changelog_section.sh CHANGELOG.md "[v1.2.9]"

Options:
  --dry-run               Validate and show what would be extracted, without printing section body.
  --help, -h              Show this help.
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --help|-h)
      print_help
      exit 0
      ;;
    --dry-run)
      DRY_RUN=1
      shift
      ;;
    --*)
      echo "Unknown option: $1"
      echo "Use --help for usage."
      exit 1
      ;;
    *)
      if [[ -z "$CHANGELOG_FILE" ]]; then
        CHANGELOG_FILE="$1"
      elif [[ -z "$SECTION_TITLE" ]]; then
        SECTION_TITLE="$1"
      else
        echo "Unexpected extra argument: $1"
        echo "Use --help for usage."
        exit 1
      fi
      shift
      ;;
  esac
done

if [[ -z "$CHANGELOG_FILE" || -z "$SECTION_TITLE" ]]; then
  echo "Usage: $0 <changelog-file> <section-title> [--dry-run]"
  echo "Example: $0 CHANGELOG.md \"[v1.2.9]\""
  exit 1
fi

if [[ ! -f "$CHANGELOG_FILE" ]]; then
  echo "Changelog file not found: $CHANGELOG_FILE"
  exit 1
fi

if [[ "$DRY_RUN" == "1" ]]; then
  if rg -Fq "## $SECTION_TITLE" "$CHANGELOG_FILE"; then
    echo "Dry-run: section $SECTION_TITLE found in $CHANGELOG_FILE."
    echo "Dry-run: would print lines from '## $SECTION_TITLE' until next '## [' heading."
    exit 0
  fi
  echo "Dry-run: section $SECTION_TITLE not found in $CHANGELOG_FILE."
  exit 1
fi

awk -v section="$SECTION_TITLE" '
  BEGIN { capture = 0 }
  /^## \[/ {
    if (capture == 1) {
      exit
    }
  }
  index($0, "## " section) == 1 {
    capture = 1
  }
  capture == 1 {
    print
  }
' "$CHANGELOG_FILE"
