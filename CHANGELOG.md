# Changelog

All notable changes to this project are documented in this file.

---

## [Unreleased]

### Major Features

- **Docker CI** – Fixed invalid `secrets` usage in step `if` conditions in **`.github/workflows/docker-release.yml`** and **`.github/workflows/docker-dev.yml`** by detecting Docker Hub credentials in a shell step and gating publish steps on `steps.dockerhub.outputs.enabled`.

- **About modal** – Navbar **About** opens a combined modal with **About** (php-rand version, Docker vs native, PHP version/SAPI, OS, server software, key extension status, full loaded extension list) and **Changelog** tabs. Data from **`about.php`** / **`includes/about_info.php`**; changelog loading unchanged but lazy-loaded on tab switch.

- **Docker (rolling dev images)** – Pushes to the **`dev`** branch republish rolling **`dev`** and **`develop`** tags to Docker Hub and GHCR via **`.github/workflows/docker-dev.yml`** (no per-commit image tags). The **`Dockerfile`** copies the build context instead of cloning GitHub so CI images match the checked-out branch/tag; **`.dockerignore`** excludes local secrets and VCS metadata.

- **jQuery 4.0** – Upgraded CDN dependency from jQuery 3.7.1 to **4.0.0** (full build) in `index.php` and `test_random_buttons.html`; updated SRI hash. Migrate 4 audit (`test_jquery4_migrate_audit.html`) found no compatibility warnings across `js/rand.js` and modules with inline jQuery handlers.

- **Tool page descriptions** – Moved per-tool information alerts into each card as muted description text across tool modules (networking, units, crontab, serialization, shellcheck, validators, text/data tools, crypto, and others); dashboard “what’s new” banner unchanged.

- **Global UI spacing preference + release guidance** – Added a new navbar **Settings → Item spacing** preference (Tight/Dense/Standard/Comfortable/Relaxed) persisted via `randUiPrefs` (`spaceScale`) and applied app-wide in `style.css` by scaling common spacing utilities (`m-*`, `p-*`, `gap-*`, `g*/gx*/gy*`) plus card spacing. Added repository agent guidance in **`AGENTS.md`** covering core architecture, mandatory changelog policy, scripted release flow (`scripts/release.sh`, `scripts/extract_changelog_section.sh`, `scripts/update-release-descriptions.php`), and a quick release checklist. Also added an inline quick release checklist comment block at the top of `scripts/release.sh`.

- **Syntax validators (many languages)** – New **Miscellaneous → Validators** checks pasted content **without executing** it. Kinds: **JSON** (`json_decode` + `JSON_THROW_ON_ERROR`), **YAML** (**`symfony/yaml`**), **XML** (DOM parse), **INI** (`parse_ini_string`), **JSON Lines** (per-line JSON, capped line count), **cron** (shared **`cron_parse_expression_fields()`** with the Crontab tool / **`dragonmantank/cron-expression`**), **PHP** (`php -l`, optional `<?php` prepend for snippets), **Python** (`ast.parse` via **`python3`/`python`**), **Ruby** (`ruby -c`), **JavaScript** (`node --check`), **shell** (`bash -n` / `sh -n` when available). Handler **`handle_syntax_validate`**, logic in **`includes/syntax_validate.php`**, UI in **`modules/syntax_validate.php`** and embed **`modules/validators.php`**. Layout/help copy refined for clarity; help panel aligned with sibling cards.
- **Navigation (Misc)** – **ShellCheck** and **Validators** are separate items under **Miscellaneous**. ShellCheck stays **`modules/shellcheck.php`**; Validators is syntax-only and embeds the validator section.
- **Text & Data tools** – **URL encoding** (RFC 3986 via `rawurlencode` / decode preview), **HTML entities** (encode, decode, both, or **auto** from content), **Levenshtein** distance with tunable insertion/replacement/deletion costs (PHP length limits documented in UI), **Metaphone** (word keys), **Minify** JS/CSS via **`matthiasmullie/minify`** plus light HTML whitespace cleanup. Handlers and navbar entries in **`includes/handlers_functional.php`** / **`includes/navbar.php`**; modules **`urlencoding.php`**, **`htmlentities.php`**, **`levenshtein.php`**, **`metaphone.php`**, **`minify.php`**.
- **Logo generator** – **Circle / rounded-rectangle** output uses **`logo_apply_shape_alpha_mask()`** so transparency matches the shape (fixes incorrect white fill from the old merge path). **Font** picker uses **`logo_discover_font_files()`** (**.ttf** and **.otf** under **`fonts/`**); bundled **DejaVu** TTFs; labels strip extensions. **Font size** **12–400** px with **number + range slider** kept in sync; presets call **`syncFontSizeUi()`**. Default **background** **`#000000`**; border row and **“Text transform”** section reorganized in **`modules/gen_image.php`**.
- **Logo generator (follow-up)** – **Export** as **PNG / WebP / JPEG** (`logo_build_raster_data_uri()`, JPEG flattened onto background color). **Multiline** text (**textarea**, 500 chars), **word-wrap**, optional **autofit** (binary-search font size to inner box), **nudge X/Y** offsets, **per-color shuffle** buttons beside each swatch, **instant** preview (**0 ms** debounce + **`textarea`** in delegated events). Layout helpers: **`logo_wrap_paragraph`**, **`logo_build_wrapped_lines`**, **`logo_autofit_font_size`**, **`logo_draw_ttf_text_block`** in **`includes/handlers_functional.php`**.
- **Crontab** – **`cron_parse_expression_fields()`** in **`includes/tooling_helpers.php`** centralizes expression validation, **`@reboot`**, and `CronExpression` construction for both the explorer and syntax validator. **UI**: layout tweaks and **diff output** styling (**`modules/crontab.php`**, **`style.css`**).
- **Docker** – Image installs **`python3`** so Python (and related CLI checks where applicable) work in containers.
- **Random data (syntax validator)** – **`js/rand.js`**: shuffle for **language** (`#syntaxValidateKind`) vs **sample textarea**; samples follow the **active** kind with avoid-repeat keys.
- **Config & repo hygiene** – **`APP_ROOT`** set with **`dirname(__FILE__, 2)`** in **`includes/config.php`**. **`.gitignore`** no longer ignores **`scripts/`** (release tooling tracked). **`README.md`** feature list reorganized (e.g. cryptography, text & data, misc tools).
- **Release automation** – **`scripts/release.sh`** rotates **`[Unreleased]`**, bumps **`docker-image.config`**, optional commit/tag/push, GitHub release, Docker publish; **`scripts/extract_changelog_section.sh`** and **`scripts/update-release-descriptions.php`** support changelog/release descriptions.

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### Syntax validation
- **Dependencies** – `composer require symfony/yaml` (^7.x); YAML lint binary under **`vendor/`** as shipped by the package.
- **Registry** – `syntax_validate` → `handle_syntax_validate()` in **`includes/handlers_functional.php`** (lazy-loads **`includes/syntax_validate.php`**).
- **Limits** – Input length capped (e.g. 200k characters); JSON Lines capped per **`syntax_validate_jsonl_max_lines()`**.
- **Privacy copy** – **`index.php`** privacy modal lists server-processed tools without a separate “validators” top-level line (validators under Misc).

#### Navigation and modules
- **`includes/navbar.php`** – Misc: `shellcheck`, `validators`; Text & Data entries for new tools as applicable.
- **`modules/validators.php`** – `#validators` shell; embeds syntax validator via `$validatorsEmbed` so nested panels are not hidden by global `.content` toggling in **`js/rand.js`**.
- **`modules/syntax_validate.php`** – Embed mode (`<section class="validators-block">`) vs standalone `#syntax_validate` for **`load_module.php?module=syntax_validate`**.

#### Random data (`js/rand.js`)
- **`SYNTAX_VALIDATE_KIND_OPTIONS`** – Language-only shuffle pool.
- **`SYNTAX_VALIDATE_SCENARIOS`** – Per-kind samples; textarea shuffle uses the selected kind (`syntaxValidateContent_<kind>`, avoid-repeat).
- **`addRandomDataButtons`** – Includes `#syntaxValidateKind`; titles distinguish language vs sample actions.

#### Docker
- **`Dockerfile`** – `apt-get install` adds **`python3`** alongside **`shellcheck`** and **`openssh-client`**.

#### Dependencies (`composer.json`)
- **`symfony/yaml`**, **`matthiasmullie/minify`** (and transitive **`path-converter`**) for the features above.

#### Styling
- **`style.css`** – Adjustments including Crontab / diff presentation.

#### Other
- **`modules/currency.php`**, **`modules/units.php`** – Minor copy or wiring updates where touched for consistency.

</details>

_Add entries here during development; rotate into a dated release section when tagging._

---

## [v1.3.0] (2026-04-03)
### Major Features

- **Navbar and IA updates** – Navigation structure now reflects the `Math` grouping better, labels were clarified (`Convert` -> **Text & Data**), and active-link handling was refactored so current tool context is highlighted more reliably.
- **Logo Generator upgrade** – `gen_image` received a substantial UI/UX refresh with improved layout, richer interactions, and live preview-focused iteration.
- **Crypto compatibility hardening** – Added RSA signing/verification padding fallback and broader algorithm compatibility updates (including Ed25519/Ed448 handling paths).
- **Shared output actions** – `copyableOutput` and related rendering paths now support optional HTML actions and more consistent action/button styling across tools.
- **Tool card intros** – Top-of-card guidance was tightened and aligned across many modules (encoding, crypto, JWT, networking, ShellCheck, SSH, and others) so descriptions and alert-style blocks are shorter, more consistent, and easier to scan.
- **Runtime/deployment refresh** – Docker image/runtime config updated for PHP 8.5 + `openssh-client`; release workflow/docs (`README`, workflow, config, ignore rules) were refined to reduce release friction.
- **Networking IP backend** – Centralized `handle_ip` flows for DNS forward/reverse lookup, IPv4 **CIDR ↔ range** conversion, and **subnet** math; subnet inputs accept dotted masks or **`/prefix`** via `handle_ip_normalize_subnet_mask()`; results render through shared `handle_ip_kv_table()` for consistent key/value output.
- **Crontab Explorer** – New **Misc** tool to validate cron expressions (including macros like `@daily` and Vixie **`@reboot`** as a one-shot at daemon start), human-readable summaries, field breakdown, and timezone-aware previous/next run listings powered by **`dragonmantank/cron-expression`**. The **full analysis** runs automatically on debounced edits (expression, timezone, run count, reference time, include-current); **Analyze Schedule** remains as an explicit action. **More options** collapses advanced fields; schedule summaries use a dedicated **human-readable** block, **`*/1` step fields** are described like wildcards in the time summary for clearer copy, and the results layout was refined.
- **ShellCheck** – New **Misc** tool to lint pasted shell scripts via the host **`shellcheck`** binary when available; JSON-backed diagnostics with severity, excerpts, and wiki links. Temp-file linting only (no persistence). Carriage returns are **stripped from pasted scripts** before linting to avoid spurious CRLF-related **SC1017** warnings.
- **Form-aware random data** – `randomDataGetCompatibleFormBundle()` extends `generateRandomData()` so random fills line up with more tool-specific forms (JWT, keypair sign/verify, SSH verification, CIDR/networking, and others) and handle `<select>` elements reliably.
- **Random shuffle samples** – Crontab and ShellCheck random-data buttons use **larger scenario pools**, **avoid picking the same scenario twice in a row**, and keep **related fields in sync** (cron + timezone; script + filename + shell dialect).

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### Navigation and Module Organization
- **Active state behavior** - Refactored navbar link state handling so current modules are marked more consistently during navigation.
- **Math grouping** - Updated navbar/module structure to better align math-related entries (including currency/units context) with dashboard category mapping updates.
- **Naming clarity** - Updated wording in `index.php` and navbar labels to better describe available tool groups.

#### Logo Generator
- **UI redesign** - Reworked `modules/gen_image.php` with improved structure, styling, and interaction flow.
- **Live feedback** - Enhanced generation flow to better support iterative logo design with preview-oriented controls.

#### Cryptography
- **RSA compatibility** - Added OpenSSL padding fallback in signing/verification paths to improve compatibility across environments.
- **Algorithm handling** - Improved support logic for modern key algorithms including Ed25519/Ed448 handling paths.

#### Shared UX Utilities
- **Reusable actions** - Extended shared output helpers to accept optional HTML actions, reducing per-module divergence and improving DRY reuse.
- **Action styling** - Standardized button/link styles around copy/download and related output actions.

#### Tool card intros
- **Consistency pass** - Reworked introductory/description blocks at the top of many `modules/*.php` files (BinHex, Brainfuck, Browser, Crontab, crypto and PEM tools, datetime, JWT, networking, ShellCheck, SSH, units, and more) for uniform structure and brevity.

#### Infrastructure and Docs
- **Docker runtime** - Updated Dockerfile and image config for PHP 8.5, removed unused OPcache install steps, and added `openssh-client`.
- **Release process docs** - Expanded release workflow guidance and environment toggle documentation in `README` and workflow-related files.
- **Repo hygiene** - Updated ignore rules for local release tooling artifacts.

#### Networking
- **Backend** - `handle_ip()`, `handle_ip_normalize_subnet_mask()`, and `handle_ip_kv_table()` in `includes/handlers_functional.php` for DNS lookup, IPv4 CIDR↔range conversion, and subnet calculations aligned with `modules/networking.php`.

#### Crontab Explorer
- **UI** - `modules/crontab.php` under **Misc**; navbar entries in `includes/navbar.php`.
- **Backend** - `handle_crontab()` in `includes/handlers_functional.php`; shared **`cron_evaluate_schedule()`**, humanization helpers, and CLI helpers in `includes/tooling_helpers.php` (loaded from `includes/_includes.php`).
- **Dependency** - `composer require dragonmantank/cron-expression` for parsing and next/previous run calculation.
- **`@reboot`** - Handled explicitly (not expressible as five cron fields); dedicated summary and “no periodic next runs” messaging instead of a parser error.
- **Live analysis** - `initCrontabLiveAnalyzeUi()` in `js/rand.js` POSTs the same payload as **Analyze Schedule** into the main results panel with debouncing and out-of-order response guarding; removed the separate `crontab_preview` action.
- **Human summary** - `crontab_human_summary_block()` presents schedule summaries in a dedicated styled block for clearer hierarchy.
- **Form UX** - “More options” `<details>` hides secondary fields; shared native `<details>` styling in `style.css`; timezone is honored in random-data payloads and live analysis.
- **Time summary** - `cron_time_summary()` treats `*/1` step fields like `*` for more natural wording; follow-up layout tweaks to the main results container in `modules/crontab.php`.

#### ShellCheck
- **UI** - `modules/shellcheck.php` under **Misc** (script textarea, optional filename, dialect, minimum severity).
- **Backend** - `handle_shellcheck()` runs `shellcheck --format=json1` via `proc_open` (`cli_run_command()` / `cli_find_binary()` in `includes/tooling_helpers.php`); structured HTML cards per finding.
- **Input** - `handle_shellcheck()` strips `\r` from script input before linting to reduce CRLF-driven SC1017 noise.
- **Random samples** - Expanded `shellcheckScenarios` in `js/rand.js` with `randomPickAvoidRepeat()`; shuffle clears the per-form bundle and syncs script, filename, and dialect from the chosen scenario.

#### Random data
- **`js/rand.js`** - `randomDataGetCompatibleFormBundle()` and `generateRandomData()` updates for context-specific bundles (JWT, keypair sign/verify, SSH verification, CIDR/networking, etc.), improved handling of `<select>` elements, and crontab timezone included when shuffling cron scenarios.

</details>

---

## [v1.2.10] (2026-03-30)
### Major Features
- **Number Generator: up to 50 digits (digit mode)** – For digit ranges that exceed native PHP integer bounds, generation uses a dedicated large-number path (requires the **GMP** extension, including `gmp_prob_prime`). Supported types include **any**, **odd**, **even**, **palindromic**, **prime**, and **composite**. **Square** and **Fibonacci** remain limited to the server’s native integer range.
- **Deployment** – Docker image builds with **GMP** (`libgmp-dev` + `gmp` extension). README documents the `gmp` requirement and large-digit behavior.
- **SSH / PEM cryptography** – **Verify** SSH or PEM material (unified public paste with auto-detect or forced PEM vs OpenSSH, optional private PEM and passphrase, `ssh-keygen -l` when available). **SSH generator** results use a **Public key output** control (PEM vs OpenSSH one-line when both exist) and always show the **private key** below. **Private/Public Keys** adds **Sign or verify a message** (server-side OpenSSL). **Client WebCrypto** key output gains the same copy UI as the server.
- **Key material ordering** – Generated SSH, keypair, and CSR bundles list **public** (and OpenSSH when present) before **private** to align with verify workflows.
- **Changelog modal** – Loads a fresh `CHANGELOG.md` on each open (`changelog.php` revalidation-friendly cache headers; AJAX without long-lived browser cache).

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### Number Generator
- **Digit limits** – Configurable digit inputs allow up to **50** digits; full native-int digit ranges are capped by `PHP_INT_MAX` (typically **18** digits for a complete min–max digit span on 64-bit builds). Explicit `int` bounds elsewhere remain limited by the length of `PHP_INT_MAX` (typically **19** digits).
- **Large-number path** – `handle_numgen()` routes oversized digit requests to string-based generation with GMP-backed length selection so min–max digit ranges stay distribution-consistent with the old numeric-range behavior.
- **UI** – `modules/gen_number.php` reflects the 50-digit cap, explains native vs large-digit behavior, and disables native-only filter options when the selected digit range exceeds the native safe limit.
- **Prime performance** – `is_prime()` uses `gmp_prob_prime()` when GMP is available instead of trial division to √n (much faster for large integers). Large-range random prime sampling skips even candidates when the range starts at 3+.
- **Large-digit primes and composites** – Digit mode above the native-int limit can generate random **prime** and **composite** values as decimal strings using `gmp_prob_prime()` (rejection sampling).
#### SSH generator and verification
- **Verify UI** – Second card on `modules/ssh_keygen.php`; unified **Public key** field with **Public key format** (auto / PEM / OpenSSH); optional PEM private and passphrase; verify results follow form field order.
- **Classification** - `crypto_classify_verify_public_key()` routes paste to PEM vs OpenSSH; legacy `verify_public_pem` / `verify_openssh_public` still honored when `verify_public_input` is empty.
- **Generator output** - `crypto_render_ssh_key_output()`: **Public key output** `<select>` toggles PEM vs OpenSSH panels only; private PEM in `crypto-ssh-private-block`; no selector when only PEM public exists; `initSshKeyOutputFormatUi()` scopes toggles to `.crypto-ssh-output-panels`.
- **Client** - `buildClientSshKeyOutput()` mirrors layout (browser: public PEM + private only; OpenSSH line requires server mode).
#### Client key output
- **WebCrypto copy UI** - `buildClientKeyOutput()` in `js/rand.js` mirrors server `copyableOutput` styling with per-block IDs for `copyToClipboard()`.
#### Key export ordering
- **Handlers** - `handle_ssh_keygen()`, `handle_keypair_generate()`, and `handle_csr_generate()` emit public/OpenSSH before private.
#### PEM sign and verify
- **Module UI** - `modules/keypair.php` sign/verify card; `initKeypairSignFormUi()` toggles sign vs verify fields; verify card separated as its own card on the page.
- **Backend** - `handle_keypair_sign_verify()`, `crypto_signature_digest_for_key()`, `crypto_digest_label()`; handlers `ssh_key_verify` and `keypair_sign_verify` registered in `getHandlerRegistry()`.
#### Changelog
- **`changelog.php`** - `Cache-Control: private, no-cache, must-revalidate` instead of long `max-age` for the markdown payload.
- **`js/rand.js`** - Changelog modal refetches on every show (`cache: false`); removed one-shot `changelogLoaded` gate; loading state while fetching.

</details>

---

## [v1.2.9] (2026-03-26)

### Major Features
- **Faster initial load** – Lazy-loaded tool modules, deferred non-critical scripts, AJAX changelog; `marked`, `highlight.js`, and `code-input` load only when a visible module needs them.
- **Cryptography suite** – Keypair, SSH, CSR, PEM/OpenSSH converter, and diagnostics modules; OpenSSH public key export; hybrid WebCrypto with server fallback.
- **Deployment** – README guidance for gzip/brotli and cache headers (Nginx/Apache).
- **Logo generator** – Built-in module replaces the `php-logogen` submodule.

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### Initial Load Optimization
- **Module loading** - Updated `index.php` to render only `dashboard` on first load; other modules now load on first navigation via new `load_module.php`.
- **Script loading** - Added `defer` to non-critical script tags (jQuery, Tabler, Marked, Highlight.js, Code Input, Axios, and local scripts) to reduce render blocking.
- **Changelog modal** - Removed eager inline `CHANGELOG.md` embedding from HTML and switched to AJAX fetch + parse when the modal is opened.
#### Navigation and Runtime
- **On-demand module fetch** - Added `loadModule()` flow in `js/rand.js` and integrated it into `navigate()` so missing module sections are fetched and inserted dynamically.
- **UX safeguards** - Added loading placeholders for lazy module fetches and improved error handling when a module fails to load.
- **AJAX submit fix for lazy modules** - Switched to delegated form submit handling in `js/rand.js` so dynamically loaded tools no longer navigate to `gen.php` on submit.
#### Asset Loading and Production Ops
- **Library lazy loading** - Removed global `marked`/`highlight.js`/`code-input` includes from `index.php`; added reusable `loadScriptOnce()`/`loadStyleOnce()` helpers with one-time caching in `js/rand.js`.
- **Feature-gated assets** - Markdown assets now load only for markdown/changelog rendering; code-input assets load only when `code-input` elements exist in a visible module.
- **Deployment docs** - Added `README.md` guidance for gzip/brotli compression and cache headers for static assets vs dynamic HTML/PHP responses.
#### Cryptography
- **New modules** - Added `modules/keypair.php`, `modules/ssh_keygen.php`, and `modules/csr.php` with matching navbar entries in `includes/navbar.php`.
- **Additional modules** - Added `modules/pem_openssh.php` (public key converter) and `modules/crypto_diagnostics.php` (runtime checks).
- **Shared backend helpers** - Added reusable keygen/export helpers in `includes/handlers_functional.php` for algorithm resolution, keypair generation, and download rendering.
- **OpenSSH output** - Added server-side OpenSSH serialization in `handle_ssh_keygen()` for RSA/ECDSA public keys, with runtime-dependent Ed25519 support and graceful fallback warnings.
- **Hybrid client/server mode** - Added browser-side key generation for keypair/SSH modules through `js/rand.js` (`generation_mode`: auto/client/server), with automatic fallback to server mode when WebCrypto support is missing or features require server processing.
- **Format conversion** - Added `handle_pem_openssh_convert()` with PEM -> OpenSSH conversion and OpenSSH -> PEM conversion via host `ssh-keygen` when available.
- **Runtime diagnostics** - Added `handle_crypto_diagnostics()` to verify algorithm availability and OpenSSH export support per algorithm.
- **Status visibility improvements** - Updated SSH/diagnostics status rendering with clearer icon badges and better contrast for dark mode.
#### Repository and Tooling
- **Submodule removal** - Removed `.gitmodules` and dropped the `php-logogen` submodule path from this repository.
- **Logo generator rebuilt in-app** - Replaced the old link-out approach with a native `modules/gen_image.php` UI backed by `handle_logo_generate()` in `includes/handlers_functional.php`.
- **Logo presets and polish** - Added quick presets (`App Icon`, `Banner`, `Initials Badge`), palette randomization, and inline hints for faster iteration.

</details>

---

## [v1.2.8] (2026-03-24)

### Major Features
- **New module: ID Generator** – Added `gen_id` with UUIDv4, ULID, and NanoID generation (bulk quantity, configurable length, uppercase option).
- **New module: JWT Inspector** – Added `jwt` with decode, verify, and sign flows for HMAC tokens (HS256/HS384/HS512).
- **Hash Generator** – Added hash rounds (1–1000) and **Use as input** support.
- **HTML Entities** – Added smart output modes (auto detect, encode only, decode only, show both) with cleaner output behavior.

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### UX and Output Consistency
- **Copyable output** – Updated output actions so `Copy` and `Use as input` render below output blocks for better readability.
- **Form submit flow** – Added shared JS submit helper (`submitToolForm`) and shared loading markup to reduce per-module divergence.
- **String Tools alignment** – Updated String Tools to use the same submit path/options as other modules.

#### Architecture and Routing
- **Router cleanup** – Updated `gen.php` to a thin router delegating to `executeHandler()` and removed the legacy inline action chain.
- **Module dedupe** – Removed legacy `encoding.php` from auto-include to avoid overlapping UI and duplicate IDs.
- **ID collision cleanup** – Renamed conflicting form IDs in active modules and added a runtime duplicate-ID warning check in JS.

#### Module Enhancements
- **Hashing** – Fixed `Use as input` to work through the functional handler path (`handle_hash`, including `action=hasher`).
- **ID Generator UX** – Improved the form by conditionally showing NanoID length only when `NanoID` is selected.

</details>

---

## [v1.2.7] (2026-03-19)

### Major Features
- **Unit Converter** (Convert → Units) – Added a single page with 12 measurement categories: Volume, Length, Weight & mass, Temperature, Energy, Area, Speed, Time, Power, Data, Pressure, Angle. Enter a value and source unit to get equivalent values for all other units in the category (client-side). Currency remains in Convert → Currency Converter.
- **Calculator** (Miscellaneous) – Added a basic arithmetic calculator with safe math evaluation (no `eval`), supporting expressions such as `25+8*3`, parentheses, and common operators.

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### Unit Converter
- **Convert → Units** – Added a new nav item under Convert with a tabbed interface for all unit types.
- **Categories** – Added Volume (L, mL, gallon, quart, pint, cup, fl oz, m³, ft³, in³), Length (m, km, cm, mm, mile, yard, foot, inch, nautical mile), Weight & mass (kg, g, mg, lb, oz, ton metric/US), Temperature (C, F, K), Energy (J, kJ, cal, kcal, kWh, eV, BTU), Area (m², km², ft², in², hectare, acre), Speed (m/s, km/h, mph, knot, ft/s), Time (s, min, h, day, week, month, year), Power (W, kW, hp metric/US, BTU/h), Data (bit, byte, KB–TB, KiB–TiB), Pressure (Pa, kPa, bar, psi, atm, mmHg, inHg), and Angle (deg, rad, grad, arcmin, arcsec).
- **UX** – Added one “From unit” dropdown per category and conversion to all other units in a copyable table.
- **Currency** – Kept currency conversion on the dedicated Convert → Currency Converter page (no duplicate in Units).

#### Calculator
- **Miscellaneous menu** – Added Calculator module for basic arithmetic expressions.
- **Safe evaluation** – Implemented tokenization and operator precedence (no `eval`), supporting +, −, ×, ÷, parentheses, and common math.

</details>

---

## [v1.2.6] (2026-03-13)

### Major Features
- **Number Generator** – Added multi-number generation (1–500), configurable separators (comma, newline, tab, pipe, custom), and fixed custom seed handling.
- **Docker** – Updated container startup to print php-rand version; build uses `PHP_RAND_VERSION` argument.
- **Secrets** – Updated Docker push secret loading to prefer `.env.local` so secrets survive git pull/merge.

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### Number Generator
- **Quantity** – Added generation of 1–500 numbers per run, joined with the chosen separator.
- **Separator** – Added presets (Comma and space, Newline, Tab, Space, Pipe) and Custom separator (free text, max 20 chars). Newline/tab display correctly in copyable output (`white-space: pre-wrap`).
- **Seed fix** – Renamed checkbox to `numgenuseseed` so it is not overwritten by a hidden field; seed is applied once for multiple numbers (reproducible sequence).
- **Copyable output** – Updated shared copyable output to use `white-space: pre-wrap` so newline-separated content displays and copies correctly.

#### Docker
- **Entrypoint** – Updated container startup to print `php-rand &lt;version&gt;`; version is set via `--build-arg PHP_RAND_VERSION=$VERSION` in `docker-pushimage.sh`.
- **Secrets** – Updated script to source `.env.local` first, then `.env`; added `.env.local` to `.gitignore` so it is not removed by merges that deleted `.env` from repo history.

</details>

---

## [v1.2.5] (2026-03-13)

### Major Features
- **Number Generator** – Digit range mode (min–max digits), new number types, and large-range fixes

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### Number Generator
- **Digit range** – Option to specify range by number of digits (e.g. 2–4 digits → 10 to 9,999) in addition to numeric From/To
- **Number types** – Prime only, Odd only, Even only (existing); new: Composite only, Perfect square only, Palindromic only, Fibonacci only
- **Large ranges** – Prime and composite use random sampling for big ranges (no more hanging on e.g. 10-digit primes); odd/even use direct formula; palindromic uses digit-based generation with rejection sampling
- **Helpers** – `digit_range_to_numeric()`, `fibonacci_in_range()`, `is_perfect_square()`, `is_palindromic()`, `random_palindromic_with_digits()` for reuse

</details>

---

## [v1.2.4] (2026-03-13)

### Major Features
- **QR Code Generator (local)** – Generate QR codes locally with chillerlan/php-qrcode; no external API, optional margin and foreground/background colors

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### QR Code Module
- **Local generation** – Replaced qr-server.com API with bundled chillerlan/php-qrcode; no data sent off-site
- **New options** – Margin (quiet zone), foreground color, background color
- **PHP 8.5** – Removed deprecated `imagedestroy()` calls (no-op since PHP 8.0)
- **UI** – Single-line “About QR Codes” alert; form preserves size, ECC, margin, and colors on submit

</details>

---

## [v1.2.3] (2025-12-29)

### Major Features
- **QR Code Generator** – Generate QR codes from any text, URL, or data (initial release; v1.2.4 adds local generation)
- **Regex Tester** – Test and debug regular expressions with match highlighting and capture groups
- **Brainfuck Converter** – Convert text to Brainfuck code or execute Brainfuck programs
- **Security Hardening** – Fixed critical code injection vulnerability in calculator
- **Improved IV Generation** – Fixed OpenSSL IV length and format validation
- **Copy Button Fix** – Fixed trailing whitespace issue when copying strings

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### New Modules
- **QR Code Generator** (Generators menu)
  - Generate QR codes with customizable size (200–500px)
  - Error correction levels (L, M, Q, H)
  - Download QR codes as PNG images
  - Uses qr-server.com API (no dependencies required)
- **Regex Tester** (Miscellaneous menu)
  - Test regular expressions in real-time
  - Display all matches with positions
  - Show capture groups
  - Regex replacement support (with $1, $2 for groups)
  - Support for common flags (case-insensitive, multiline)
  - Pattern validation with error messages
- **Brainfuck Converter** (Convert menu)
  - Text → Brainfuck: Convert any text to Brainfuck code that outputs that text
  - Brainfuck → Text: Execute Brainfuck code and capture the output
  - Full Brainfuck interpreter with 30,000 cell tape
  - Bracket matching validation
  - Safety limits to prevent infinite loops
  - Statistics display (code length, compression ratio)

#### Security Fixes
- **CRITICAL: Code Injection Fix** – Replaced dangerous `eval()` in calculator with safe math parser
  - Created `safeMathEval()` function using tokenization and operator precedence
  - Eliminates arbitrary code execution vulnerability
  - Maintains full calculator functionality
- **Information Disclosure Fix** – Debug mode now disabled by default
  - Requires `DEBUG_MODE` constant to enable
  - Prevents exposure of sensitive `$_REQUEST` data
- **Input Validation** – Added comprehensive validation to calculator function

#### Bug Fixes
- **OpenSSL IV Generation** – Fixed IV length calculation and hex validation
  - Removed incorrect division by 2 in IV length calculation
  - Added hex format validation before conversion
  - Properly converts hex IV to binary for OpenSSL functions
  - Improved error messages for invalid IV formats
- **Cipher Selection** – Fixed null cipher selection in dropdown
  - Added default selected cipher (aes-256-cbc)
  - Validates cipher before use to prevent errors
- **Random Data Generation** – Fixed IV/Key random generation to use hex strings
  - Context-aware detection for OpenSSL form
  - Generates valid hexadecimal strings for IV and Key fields
- **Copy to Clipboard** – Fixed trailing whitespace being copied
  - Added `.trim()` to `copyToClipboard()` function
  - Ensures clean text copying without extra spaces

#### Code Quality
- **Code Standardization** – Converted all `Null`, `True`, `False` to lowercase
  - Updated throughout codebase for consistency
  - Follows PHP coding standards
- **Null Safety** – Added null coalescing operators to prevent deprecation warnings
  - Fixed `htmlspecialchars()` null parameter warnings
  - Applied across all modules and handlers

</details>

---

## [v1.2.2] (2025-12-07)

### Major Features
- **Global UTF-8 encoding** – Consistent UTF-8 across all modules
- **Complete PHPDoc documentation** – 30+ functions fully documented
- **Context-aware random data** – Smart sample data based on field type
- **Input validation framework** – Security hardening with DOS prevention

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### UTF-8 Configuration
- Added `ini_set('default_charset', 'UTF-8')` and `mb_*` function defaults
- Applied in `includes/config.php` for app-wide consistency

#### PHPDoc Documentation
- 20+ functions in `includes/functions.php`
- 13+ handlers in `includes/handlers_functional.php`
- Complete @param, @return, @example tags

#### Context-Aware Random Data
- Calculator: `25+8*3`
- Networking: IPs, CIDR, domains
- Diff/Serialization: multi-line text, JSON/XML
- New helpers: `randomCalculation()`, `randomCIDR()`, `randomIPRange()`, `randomSubnetMask()`, `randomDomain()`, `randomYAML()`, `randomXML()`, `randomIPv6()`

#### Input Validation
- Master `validateInput()` function with 10+ types (string, number, email, URL, IP, hostname, hex, JSON, base64)
- All 9 handlers validated: stringgen, hash, numgen, base, hex, rot, openssl, datetime, stringtools
- Length limits: 100K–1M chars depending on handler
- Range validation, whitelist checking, consistent error messages

#### Other Changes
- Networking outputs now render clean HTML
- README updated with DNS lookup and enhanced tools
- Validation simplified to support optional fields

</details>

---

## [v1.2.1]

### Major Features
- **Random Data Buttons** – Auto-generate contextual sample data for all inputs
- **Dashboard redesign** – Modern UI with stats, categories, and feature highlights

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### Random Data Buttons
- Smart context detection (email, URL, IP, hex, JSON, etc.)
- Visual feedback with shuffle icon and success checkmark
- Generates: emails, URLs, IPs, hex hashes, base64, JSON, code snippets, lorem ipsum

#### Dashboard Updates
- Hero section with call-to-action buttons
- Stats dashboard showing tool counts by category
- Color-coded categories (generators: teal, cryptography: blue, encoding: purple, convert: orange, misc: pink)
- Fun facts section with educational content

</details>

---

## [v1.2.0]

### Major Features
- **UI/UX Modernization** – Complete redesign of all 15+ modules
- **Pure PHP diff** – No xdiff extension required
- **Copyable outputs** – Consistent styled output boxes everywhere

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### New Features
- Global `copyableOutput()` function for styled output boxes
- Global `copyToClipboard()` JavaScript with Clipboard API fallback
- Timezone selector in datetime module
- Pure PHP diff algorithm

#### Modernized Modules (15+)
All modules updated with:
- Split input/output layouts (responsive grid)
- Gradient backgrounds themed by purpose
- Large form controls (form-select-lg/form-control-lg)
- Empty states with centered icons
- Consistent copyable outputs

Specific updates:
- **OpenSSL**: amber gradient, security alerts
- **Hash**: multi-algorithm display, individual copy buttons
- **Generators**: large inputs, seed toggle, copyable results
- **Networking**: 5 tools redesigned (DNS, CIDR, range, subnet, IP/hex)
- **Diff**: unified viewer, side-by-side layout
- **Serialization**: cyan gradient, JSON focus
- **Markdown**: live preview, auto-render
- **Minify**: compression stats display

#### Fixes
- ROT bruteforce toggle sync
- Minify backend method calls
- Datetime unit key consistency
- Copy-to-clipboard HTTP/HTTPS support
- Dark theme contrast improvements

</details>

---

## [v1.1.1]

### Major Features
- **New modules**: Metaphone, Levenshtein, Diff, Currency converter
- **Networking**: DNS lookup tool

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

- Module: metaphone (phonetic key generation)
- Module: levenshtein (string distance)
- Module: diff (text comparison)
- Module: currency converter
- Network tool: DNS lookup
- String generator: info output now in table format
- String tools: output to textbox disabled by default

</details>

---

## [v1.1.0]

### Major Features
- **New encoding tools**: URL encoding, HTML entities
- **New converters**: Markdown, Minify
- **Spin the wheel** – Now an actual wheel animation

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### New Modules
- Encoding: urlencoding
- Encoding: htmlentities
- Convert: markdown
- Convert: minify

#### Changes
- Spin the wheel: actual wheel animation
- Files moved to `includes` directory
- Navbar: reworked and reorganized with dropdowns
- Dashboard: moved disclaimer to top
- Serialization: moved from misc to convert

#### Fixes
- Navbar: removed duplicate hash

</details>

---

## [v1.0.2]

### Major Features
- **Calculator module** added

---

## [v1.0.1]

### Major Features
- **Logogen submodule** added

---

## [v1.0.0]

### Major Features
- **Changelog modal** with marked.js
- **Updated dependencies**: Tabler v1.4.0, Highlight.js v11.11.1

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

- Changelog modal implementation
- Marked.js for markdown rendering
- Tabler updated to v1.4.0
- Highlight.js updated to v11.11.1
- Modal background opacity and color adjusted
- Fixed href on nav changelog button

</details>
