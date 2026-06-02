# AGENTS.md

This file is guidance for coding agents (and contributors) working in this repository.

## Quick Release Checklist

1. Confirm all shipped changes are documented in `CHANGELOG.md` under `## [Unreleased]`.
2. Run `./scripts/release.sh --dry-run` and verify the changelog rotation output looks correct.
3. Run `./scripts/release.sh <X.Y.Z>` to rotate changelog and bump `docker-image.config`.
4. Create/push release commit + annotated tag `vX.Y.Z` (or follow script prompts).
5. Ensure GitHub release and Docker publish steps are completed (prompts or env toggles).
6. Sync `RELEASE_BRANCH` to `MAIN_BRANCH` (direct merge or PR mode per repo policy).

## Project Core Concepts

- `rand` is a modular PHP web app with many tools grouped by category in `includes/navbar.php`.
- Tool UIs live in `modules/*.php`.
- Request handling is action-driven via `gen.php` and handler functions in `includes/handlers_functional.php`.
- Shared helpers and cross-tool logic belong in `includes/` (prefer reusable helpers over per-module duplication).
- Frontend behavior is mostly in `js/rand.js`; global UI preferences are applied early in `js/rand_ui_boot.js`.
- Global styling is in `style.css`.

## Architecture Rules

- Keep changes DRY: if logic/style appears in multiple places, create or reuse a shared helper.
- Prefer extending existing handler/helper patterns instead of adding one-off code paths.
- Preserve current UX patterns (cards, copyable output, delegated events, module-level forms).
- Avoid unnecessary rewrites; make focused, incremental edits.

## Changelog Policy (Mandatory)

`CHANGELOG.md` must be updated for every meaningful code change.  
No feature, fix, refactor, UX update, dependency update, or behavior change should be merged without a changelog entry.

### Required Workflow

1. Add/update entries under `## [Unreleased]` while developing.
2. Place the update in the appropriate section:
   - `### Major Features` for user-visible highlights.
   - Optional `<details>` block for deeper technical breakdown grouped by topic.
3. Use concise bullets with clear scope and impacted areas/files when helpful.
4. At release time, rotate `[Unreleased]` into a dated version section (existing repo pattern), then start a fresh `[Unreleased]`.

### Repository Changelog Format (Current Standard)

`CHANGELOG.md` follows this shape:

1. `# Changelog` header + short intro
2. `## [Unreleased]`
3. `### Major Features` bullets
4. Optional:
   - `<details>`
   - `<summary>📋 Detailed Changes (click to expand)</summary>`
   - grouped subsections (e.g. `#### Navigation`, `#### Docker`, etc.)
5. Release sections like `## [v1.3.0] (YYYY-MM-DD)` with the same pattern

Agents should preserve this format unless explicitly asked to change changelog structure.

## Pull Request / Change Expectations

- Include a changelog update in the same branch/PR as the code change.
- If no changelog entry exists, treat the task as incomplete.
- When uncertain where an entry belongs, add it to `[Unreleased]` first and keep wording factual and brief.

## Release Process (Scripts)

This repository has an opinionated scripted release flow in `scripts/`.

### Main Script: `scripts/release.sh`

- Purpose:
  - Rotate `## [Unreleased]` into `## [vX.Y.Z] (YYYY-MM-DD)` in `CHANGELOG.md`.
  - Reset a fresh `## [Unreleased]` block with `### Major Features`.
  - Bump `VERSION=` in `docker-image.config`.
  - Optionally create commit/tag/push, GitHub release (`gh`), Docker publish, and merge release branch back to main.
- Basic usage:
  - `./scripts/release.sh` (suggests next patch from top release in changelog)
  - `./scripts/release.sh 1.2.11`
  - `./scripts/release.sh --dry-run`
  - `./scripts/release.sh 1.2.11 --publish-only` (for already-pushed tags)
- Important env toggles:
  - `CREATE_GH_RELEASE=1`
  - `PUBLISH_DOCKER=1`
  - `MERGE_RELEASE_TO_MAIN=1`
  - `MERGE_TO_MAIN_VIA_PR=1`
  - `RELEASE_BRANCH` (default `dev`)
  - `MAIN_BRANCH` (default `main`)

### Supporting Scripts

- `scripts/extract_changelog_section.sh`
  - Extracts one release section from `CHANGELOG.md` by title (example: `[v1.2.9]`).
- `scripts/update-release-descriptions.php`
  - Updates existing GitHub release notes from changelog sections via `gh release edit`.
  - Supports `--dry-run`.

### Release Expectations for Agents

- Before releasing, ensure `[Unreleased]` is accurate and complete.
- Do not release if changelog entries are missing for shipped changes.
- After release/tag push, ensure GitHub release notes and Docker publication steps are completed (script prompts or env flags).
- Keep default branch aligned with the release branch per repo policy (merge or PR path in `release.sh`).
