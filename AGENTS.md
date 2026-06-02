# AGENTS.md

This file is guidance for coding agents (and contributors) working in this repository.

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
