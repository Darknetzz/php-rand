# Changelog

All notable changes to this project are documented in this file.

---

## [Unreleased]

### Major Features

- **Dependency updates** – **symfony/yaml** 8.1.10 → 8.1.12 fixes CVE-2026-123792 (GHSA-hwfg-qwvh-2wfx), where a crafted YAML document could make parsing take a very long time. Also **matthiasmullie/minify** 1.3.75 → 1.3.76 (`composer.lock`, `vendor/`). Frontend: **jQuery** 4.0.0 → 4.1.0 (new SRI hash), **@tabler/core** 1.7.3 → 1.7.5, **lucide-static** 1.66.0 → 1.80.0 (`index.php`, test pages), bundled **axios** 1.21.0 → 1.21.1 (`js/axios.min.js`). **marked** is now pinned to 18.3.0 instead of the unversioned jsDelivr URL, so a new major release can't change Markdown rendering unannounced (`ensureMarkdownAssets()`, `js/rand.js`). CI: `docker:27`/`docker:27-dind` → `docker:29`, `alpine:3.22` → `alpine:3.24` (`.gitlab-ci.yml`).
- **Password Strength Checker** – New **Cryptography → Password Strength** tool (`modules/password_strength.php`, GitLab #18). It shows a 0–4 score with a coloured meter, the estimated guesses and bits, zxcvbn's warning and suggestions (common passwords, keyboard patterns, dates, repeats, sequences), crack-time estimates for four attack scenarios (rate-limited and unlimited online, slow and fast offline hashes) and a table of the patterns found in each part of the password. **It runs entirely in the browser**: the field is not in a submitting form, has `autocomplete="off"`, and the password is never sent to the server or stored, which the page says up front. A show/hide toggle switches the field type. **zxcvbn 4.4.2** is loaded on demand from cdnjs, pinned like highlight.js (`ZXCVBN_VERSION` in `js/rand.js`), so About → External libraries lists it automatically (CDN rather than a bundled copy, following the repo's other frontend libraries). Logic is in `initPasswordStrengthUi()` and `pwstrengthDescribeMatch()` (`js/rand.js`), styles are `.pwstrength-*` in `style.css`.
- **Placeholder Data** – New **Generators → Placeholder Data** tool (`modules/placeholder_data.php`, GitLab #11) with two subpages. **Lorem ipsum** makes N paragraphs, sentences or words, optionally starting with "Lorem ipsum dolor sit amet" (`handle_lorem`, `lorem_sentence()`). **Fake data** makes up to 1,000 rows of names, emails, usernames, phone numbers, addresses, IPv4/IPv6, MAC addresses, URLs and dates as tab-separated lines, CSV or JSON (`handle_fake_data`, `fake_data_row()`). It only uses reserved values: `example.com`/`.org`/`.net` and `.test` domains, the IPv4 documentation ranges (`192.0.2.0/24`, `198.51.100.0/24`, `203.0.113.0/24`), `2001:db8::/32`, the fictional `555-01xx` phone block and locally administered MACs. Both support the optional seed. Word lists live in `includes/data/lorem_words.txt` and `includes/data/fake_data.php`; the lorem list is read by `lorem_words()` (`includes/functions.php`) and passed to the random-fill buttons as `window.RAND_LOREM_WORDS` (`index.php`), replacing the copy in `js/rand.js`. Limits are `LOREM_MAX` and `FAKE_DATA_MAX_ROWS` in `includes/config.php`.
- **ID timestamp decoder** – The ID Generator has a new **Decode timestamp** subpage (`#gen_id/decode`, GitLab #16) that pulls the embedded time and fields out of an ID, auto-detecting the type (`id_detect_type()`) or taking an override. **ULID**: timestamp and randomness parts (`id_decode_ulid()`). **UUID**: version and variant, plus the timestamp for v1/v6 (100 ns precision, clock sequence, node with a MAC/random note) and v7; nil and max UUIDs and `urn:uuid:`/braces are recognised (`id_decode_uuid()`). **Snowflake**: timestamp, machine ID split into its two 5-bit halves and sequence, with Twitter/X, Discord and Unix epoch presets (`SNOWFLAKE_EPOCHS` in `includes/config.php`) or a custom epoch (`id_decode_snowflake()`). The time is shown in UTC, in a chosen timezone (defaults to the browser's), as ISO 8601, Unix ms and relative (`relative_time_humanize()`). Handler `handle_id_decode` (`includes/handlers_functional.php`). New shared helpers in `includes/functions.php`: `timezone_select_html()` (now also used by Date & Time instead of its own closure) and `copyable_table_html()` (now also used by the Relative Time result tables).
- **Colour Generator** – New **Generators → Colour Generator** tool (`modules/colour_gen.php`, GitLab #12). **Single colour** shows one large swatch; **Palette** shows five from a scheme (random, analogous, complementary, triadic, monochrome). Each swatch has copyable HEX, RGB and HSL values, black or white text picked by WCAG contrast, and a lock: **Generate** re-rolls only the unlocked colours, building the scheme around the first locked one. Runs in the browser with `crypto.getRandomValues()`. New shared colour helpers in `js/rand.js` (`secureRandomInt()`, `randomHexColour()`, `hexToRgb()`, `rgbToHsl()`, `hslToHex()`, `contrastTextColour()`); the logo generator's random colours use `randomHexColour()` instead of its own `Math.random()` helper.
- **Dice notation** – The Dice Roller takes tabletop notation in a new **Dice notation** field (GitLab #13): `3d6`, `+K`/`-K` modifiers, keep/drop (`4d6kh3`, `4d6dl1`, `2d20kh1` advantage, `2d20kl1` disadvantage; `k` = `kh`, `d` = `dl`), `d%` for d100, and several groups in one roll (`1d20+2d6+3`). The summary shows each group with dropped dice in parentheses and the total, e.g. `4d6kh3+3: [6, (1), 5, 3] + 3 = 17`; dropped dice fade out in the tray once they land. Parsed server-side by `parse_dice_notation()` / `handle_dice_notation_roll()` (`includes/handlers_functional.php`), with the total dice still capped by `DICE_MAX_COUNT` and sides by `DICE_MIN_SIDES`/`DICE_MAX_SIDES`; new `DICE_NOTATION_*` limits in `includes/config.php`. Seed and Recent results work as before. `dice_tray_html()` takes optional per-die sides and dropped flags (`data-dice-sides-list`, `data-dice-dropped`), read by `renderDiceRoll()`.
- **Chmod Calculator** – New **Miscellaneous → Chmod Calculator** tool (`modules/chmod.php`, GitLab #17). Tick read/write/execute for owner, group and others plus setuid, setgid and sticky, or type an octal (`755`, `4755`) or symbolic (`rwxr-xr-x`, `rwsr-xr-T`, ls-style `-rw-r--r--`) mode; all three views stay in sync and a copyable `chmod 755 file` command follows along. Runs entirely in the browser (`initChmodUi()`, `chmodToSymbolic()`, `chmodParseSymbolic()` in `js/rand.js`); invalid input is flagged without touching the other views.
- **Spin the Wheel random buttons fixed** – Wheel rows no longer collect rows of stray shuffle buttons under each item. `addRandomDataButtons()` (`js/rand.js`) treated each wheel row as an `.input-group` and put the button after the row, so its duplicate check never saw it and every re-run (page load, Add Item) added another one for both the item and the weight field. Wheel rows now get one button inline next to the item text, and none for the weight.
- **Card Draw** – New **Generators → Card Draw** tool (`modules/card_draw.php`, GitLab #9). It draws 1–52 cards, or 1–54 with the two jokers, without replacement from a deck shuffled server-side with `rng_shuffle()` (`card_deck()`, `handle_card_draw`). Cards are drawn as playing cards (rank corners, red ♥♦ / black ♠♣ suits) dealt in one by one with a CSS animation that respects `prefers-reduced-motion`. A copyable summary reads like `A♠, 10♥, Q♦`. The tool supports the optional seed and keeps the shared Recent results list.
- **Magic 8-Ball** – New **Generators → Magic 8-Ball** tool (`modules/magic_8ball.php`, GitLab #10). Ask an optional question and shake the ball for one of 20 original answers (10 positive, 5 neutral, 5 negative, `eightball_answers()`) or a plain Yes/No. The ball shakes and the answer fades into its window, tinted green, amber or red by tone, with no motion under `prefers-reduced-motion`. The question is echoed in a copyable `Q:`/`A:` summary. It is picked server-side (`handle_eightball`), supports the optional seed and keeps a Recent results list. In `js/rand.js`, both new tools add to the history once their CSS animation has finished.
- **Passphrase Generator** – New **Generators → Passphrase Generator** tool (`modules/gen_passphrase.php`, GitLab #7) for diceware-style passphrases like `correct-horse-battery-staple`. Words come from the bundled **EFF large wordlist** (7,776 words, CC BY 3.0, `includes/data/eff_large_wordlist.txt`, read by `passphrase_wordlist()`) and are picked server-side with `random_int()`. Options: 3–20 words, separator (hyphen, space, dot, underscore, none), capitalisation (none, first letter, random), append a digit and/or a symbol, and up to 100 passphrases at once. Handler **`handle_passphrase`** shows the entropy next to the result (`words × log2(7776)`, plus 1 bit per word for random capitalisation and the digit/symbol pools). A shared **`entropy_badge_html()`** (`includes/functions.php`) draws it and rates it weak/fair/strong/very strong, and the String Generator's Info table now shows it too. The word list is listed in About → External libraries as bundled data. Limits are `PASSPHRASE_*` in `includes/config.php`.
- **List Shuffler** – New **Generators → List Shuffler** tool (`modules/list_shuffler.php`, GitLab #8): paste a list with one item per line, then **Shuffle** it, **Pick** N random winners with no repeats, or **Split into groups** by number of groups or by items per group. Groups are dealt round-robin, so sizes differ by at most one ("split 23 people into 4 teams" gives 6/6/6/5). Blank lines are ignored, duplicates can be removed, and each group plus the whole result is copyable. Handler **`handle_list_shuffle`** shuffles server-side with a new **`rng_shuffle()`** (`includes/functions.php`, Fisher–Yates on `rng_int()`, so it is secure by default and supports the optional seed). `utf8_shuffle()` uses it too. Limits are `LIST_SHUFFLE_MAX_ITEMS` and `LIST_SHUFFLE_MAX_BYTES` in `includes/config.php`.
- **Recent results for dice and coins** – Dice Roller and Coin Flip now list the last 20 results under the tool, newest first, with the time and a **Clear history** button (GitLab #14). Coin Flip also keeps a running heads/tails tally with counts and percentages, which goes on counting after old flips drop off the list. A result is added once the dice land, so the list doesn't give it away early. It is a shared component other random tools can reuse: `result_history_html()` (`includes/functions.php`) places the list, `result_history_attrs()` (`includes/handlers_functional.php`) tags a response with `data-history-*` (added to `dice_tray_html()`), and `recordResultHistory()` / `renderResultHistory()` (`js/rand.js`) store it per tool in `localStorage` (`rand.history.<tool>`). `renderDiceRoll()` now returns a promise that resolves when the roll finishes. Styles are `.rand-history*` in `style.css`.
- **Seeded dice and coins** – Dice Roller and Coin Flip have an optional **Use custom seed** switch, like the Number Generator (GitLab #15). An empty seed keeps `random_int()`. A seed switches to seeded `mt_rand()`, so the same seed and settings give the same result, and the output says "Not cryptographically secure" and shows the seed used. All three tools share one implementation now: `seed_field_html()` (`includes/functions.php`) for the form fields, `req_seed()` and `rng_seed_notes()` (`includes/handlers_functional.php`) for reading the seed and the notes under the result, and one delegated `[data-seed-toggle]` handler in `js/rand.js` that replaces the Number Generator's inline script. `resolve_numgen_seed()` is renamed `resolve_rng_seed()`. The Number Generator now also treats an empty seed with the switch on as "no seed" instead of an error.
- **Programming cases in String Tools** – New **PascalCase**, **snake_case**, **CONSTANT_CASE** and **dot.case** buttons in String Tools (GitLab #19). A shared word splitter, `stringtools_split_words()` (`includes/handlers_functional.php`), splits on spaces, `-`, `_`, `.`, other punctuation and camelCase boundaries (`myVarName` → `my_var_name`, `XMLHttpRequest` → `xml_http_request`), and `stringtools_convert_case()` converts each line separately so lists of names keep their lines. **camelCase** and **kebab-case** use the same splitter now: camelCase used to split only on spaces, and kebab-case turned `myVarName` into `myvarname`. Multibyte letters are kept (`blåbær_syltetøy`); kebab-case still transliterates to ASCII for URLs.
- **Rolling `dev-latest` release** – Every push of `dev` to GitLab now publishes a **Dev Latest** pre-release on GitHub and GitLab, like jotty-android. A new `dev-latest` job in `.gitlab-ci.yml` runs `scripts/publish-dev-latest.sh`, which moves the `dev-latest` tag to the commit on both remotes and recreates the GitHub pre-release (never marked latest). It also creates or updates the GitLab release through the job token. Notes link the commit and the `:dev` Docker image and include the `[Unreleased]` changelog section. The job waits for GitHub `dev` to catch up and skips if a newer commit has replaced it. `--dry-run` previews the notes. Documented in `README.md` and `AGENTS.md`.
- **Coin Flip** – New **Generators → Coin Flip** tool (`modules/coin_flip.php`): flip 1–100 coins (`COIN_MAX_COUNT` in `includes/config.php`). Handler **`handle_coin_flip`** flips server-side with `random_int` and reuses the Dice Roller's 3D d2 coin, drawn in gold with **H**/**T** faces, then shows a copyable summary such as `3 coins: Heads, Tails, Heads (2 heads, 1 tail)`. Shared **`dice_tray_html()`** builds the markup for both tools; `renderDiceRoll()` (`js/rand.js`) takes optional face labels (`data-dice-labels`) and the dice colour is now a `--dice-hue` CSS variable (`style.css`).
- **Dice Roller** – New **Generators → Dice Roller** tool (`modules/dice_roller.php`): choose the number of dice (1–100) and sides per die (2–1000, default 6). Handler **`handle_dice_roll`** rolls server-side with `random_int`; `renderDiceRoll()` (`js/rand.js`) tumbles a 3D CSS die per roll that has as many faces as the die has sides: a coin for d2, the real polyhedra for d4/d6/d8/d12/d20 (pips on d6), a d10-style trapezohedron for other even counts, a prism (barrel die) for odd counts, and a shaded ball above 60 sides (`DICE_SHAPE_MAX_FACES`). Faces are numbered 1–N with opposite faces summing to N+1 where the shape allows, 6/9-style numbers are underlined, faces are shaded from a fixed light, and the rolled face lands toward the viewer. When the rolled face is too small to read (many-sided dice), a result badge fades in over the die once it lands (`DICE_BADGE_MIN_FONT`). It then reveals a copyable summary such as `3d6: 4, 1, 6 = 11`. Respects `prefers-reduced-motion`. Limits live in `includes/config.php`.
- **Release script without a terminal** – `scripts/release.sh` no longer aborts with `__v: unbound variable` when run without a controlling terminal (CI, agents): a new `has_tty()` actually opens `/dev/tty` instead of checking `[[ -r /dev/tty ]]`, which is true even with no terminal. The "Next steps" text no longer claims GitHub Actions creates the GitHub Release (Actions are unused); it prints the `gh release create` command instead.
- **Min per type in String Generator** – The **Character Rules** card has a new **Min per type** row next to **Max per type**: set a minimum count of Numbers / Lowercase / Uppercase / Special characters (e.g. Min Numbers = 2 guarantees at least two digits). `stringgen_generate()` (`includes/functions.php`) draws each type's minimum first, fills the rest from the pool with the max caps, then shuffles. `stringgen_validate_limits()` takes the minimums too and rejects a min for a type the selected sets don't contain, a min above its max, and minimums that add up to more than the length. `handle_stringgen` reads `min_*` / `max_*` in one loop, and the module renders both rows from one loop (`modules/gen_string.php`).
- **Hexadecimal strings** – String Generator gained **Hexadecimal (lowercase)** `0-9a-f` and **Hexadecimal (uppercase)** `0-9A-F` charset toggles (flags `x` / `X` in `stringgen_charset_chars()`, `includes/functions.php`; accepted by `handle_stringgen`). Turning one on clears the other built-in sets and the opposite hex case, so the output is pure hex (custom characters stay if enabled) (`modules/gen_string.php`).
- **String Generator character rules** – New **Character Rules** card in the String Generator (`modules/gen_string.php`): **Avoid ambiguous characters** drops look-alikes `0 O o 1 l I |` (`STRINGGEN_AMBIGUOUS_CHARS`), and **Max per type** caps how many Numbers / Lowercase / Uppercase / Special characters a string may contain (empty = no limit; e.g. Special = 1). Generation moved into a shared `stringgen_generate()` used by `genStr()` / `genStrCrypto()` (`includes/functions.php`): types drop out of the pool once they hit their cap, then the result is shuffled with the same RNG so capped characters don't cluster at the start. `handle_stringgen` rejects limits that can't fill the requested length, and the combinations count is marked as an upper bound when limits are set.
- **Multibyte charsets fixed** – Symbols (`¤`, `´`), Extended (`ƒ†‡™•`) and custom characters were split byte-by-byte with `str_split()`, producing broken characters and an inflated pool size; `stringgen_charset_chars()` now splits with the shared `utf8_chars()` helper.
- **Unbiased secure strings** – `genStrCrypto()` (`includes/functions.php`) now picks characters with `random_int()` instead of `ord(random_bytes(1)) % count`, which favoured some characters whenever the pool size didn't divide 256 (e.g. the 62-char alphanumeric set). UI label and result banner now say `random_int()`.
- **Secure random numbers by default** – Number Generator now uses `random_int()` (CSPRNG) unless a custom seed is set; a seed switches to seeded `mt_rand()` (reproducible, not secure), so the two modes are mutually exclusive. New shared helpers in `includes/functions.php`: `rng_seed()`, `rng_is_seeded()`, `rng_int()`, `rng_pick()`, `rng_bytes()`, `rng_gmp_range()` (rejection sampling over `random_bytes()`) and `rng_source_notice()`. `numGen()` and the large-digit (GMP) path use them instead of `mt_rand()` / `array_rand()` / `gmp_random_range()`, which also makes seeded large-digit output reproducible (`gmp_random_range()` ignored `mt_srand()`). Number and String Generator output now states which RNG was used (`.rng-notice` in `style.css`); the String Generator's non-secure mode is labelled `array_rand()`. Unweighted Spin the Wheel picks with `random_int()`. Removed the unused legacy `spinWheel()` helper. GitLab #6.
- **Enter-key form submit** – Pressing Enter in a tool form now sends the submit button's `name`/`value` (GitLab #5). Before, the handler only looked at `$('.genBtn:focus')`, which is empty when focus is in a text field. As a result, **OpenSSL** (`tool=encrypt|decrypt`) and **Bin↔Hex** (`tool=bin2hex|hex2bin`) answered "Invalid tool selected.", or reused the button clicked last. New `getFormSubmitter()` (`js/rand.js`) uses `SubmitEvent.submitter`, then a focused submit button in the form, then the form's first submit button. This also fixes clicks in Safari, which doesn't focus buttons. Removed the dead `button[clicked=true]` code. `submitToolForm()` now serializes after adding its hidden `action`/`responsetype` inputs.
- **UTF-8 in string tools** – Multibyte text (æ, ø, å, ™, …) no longer turns into broken bytes (GitLab #4). New shared helpers in `includes/functions.php`: `utf8_chars()`, `utf8_reverse()`, `utf8_shuffle()` (Fisher–Yates with `random_int`), `utf8_invert_case()`, `utf8_to_ascii()` (intl `Any-Latin; Latin-ASCII`, iconv fallback) and `utf8_levenshtein()`. String Generator builds its character pool per character instead of per byte, so symbols (`¤`, `´`), the extended set (`ƒ†‡™•`) and custom characters give valid UTF-8 and a correct "possible combinations" count. String Tools (`handle_stringtools`): **reverse**, **shuffle** and **inverted case** work per character; **slugify**/**kebab-case** transliterate first (`blåbær` → `blabaer`); **remove punctuation/symbols/extended symbols** keep non-ASCII letters and **remove letters** removes them; invalid UTF-8 input is rejected with HTTP 422. Levenshtein counts characters, not bytes (`a` → `å` is 1, not 2), and the 255-byte cap is replaced by `LEVENSHTEIN_MAX_CHARS` (1,000 characters, `includes/config.php`).

_Add entries here during development; rotate into a dated release section when tagging._

---

## [v1.5.0] (2026-11-02)

### Major Features

- **Release script** – `scripts/release.sh` no longer copies the `[Unreleased]` placeholder line into the new release section, and keeps a blank line after the version heading.
- **OPcache status + fresh Docker base** – About → Key PHP extensions showed OPcache as missing because the extension registers as `Zend OPcache`; it now checks that name and reports whether the opcode cache is actually enabled (`opcache_get_status()`), since PHP 8.5 always loads it (`includes/about_info.php`). OPcache was already on in the container (`opcache.enable=1` in the `Dockerfile`). `docker-pushimage.sh` now builds with **`--pull`** so images always use the current `php:8.5-apache` base (a cached older base could fall below the PHP 8.5.12 minimum).
- **GitLab releases** – Version tags now get a GitLab Release as well as the GitHub one. New **`gitlab-release`** job in **`.gitlab-ci.yml`** (tag `vX.Y.Z`, `glab` image, `CI_JOB_TOKEN`) uses the `## [vX.Y.Z]` section of `CHANGELOG.md` via `scripts/extract_changelog_section.sh` as release notes, and runs independently of the Docker job. **`scripts/release.sh`** pushes the branch and tag to the **`gitlab`** remote too (override with `GITLAB_REMOTE`; skipped if the remote is missing), including in `--publish-only`. `AGENTS.md` updated.
- **Square logo + README header** – Added a 1:1 project logo in the green dice / **RAND** style of the social preview: **`images/logo.png`** (1024×1024, rendered with Ubuntu Bold) and editable source **`images/logo.svg`**. Removed the pink `docs/images/banner-gradient.png` banner from the top of **`README.md`**; the social preview is now the first image.
- **Filled buttons (global toggle)** – Action buttons that used `btn-outline-*` are now filled (`btn-primary`, `btn-light`, …). One setting controls it: **`BTN_OUTLINE`** in `includes/config.php` (`false` = filled, `true` = outline), applied through the new **`btnClass()`** helper in PHP (`includes/functions.php`) and JS (`js/rand.js`, reads `window.RAND_BTN_OUTLINE` set in `index.php`). Radio toggle groups (`btn-check` labels: logo format/shape, number mode, relative-time style) intentionally stay outline so the selected option remains visible. Copy-button feedback now restores the button's original classes instead of hard-coding the outline variant, and `index.php`'s `copyToClipboard()` reuses `setCopyButtonFeedback()`.
- **Minimum PHP 8.5.12** – `composer.json` now requires `php >=8.5.12`, so Composer's generated `vendor/composer/platform_check.php` stops the app with a clear error on older PHP. README requirements and apt example moved to PHP 8.5 (`php8.5-*`). The Docker image (`php:8.5-apache`, currently 8.5.12) already meets it.
- **Composer dependencies** – Updated all PHP packages to latest: **chillerlan/php-qrcode** `5.0.5` → `6.0.1` (major), **symfony/yaml** `7.4.8` → `8.1.10` (major), **dragonmantank/cron-expression** `3.6.0` → `3.6.1`, **symfony/polyfill-ctype** `1.33.0` → `1.37.0`, **chillerlan/php-settings-container** `3.2.1` → `3.3.0` (4.x is blocked by php-qrcode's `^3.2.1`); `symfony/deprecation-contracts` is no longer needed. `includes/qrcode.php` now uses php-qrcode 6's `outputInterface => QRGdImagePNG::class` (the `outputType` / `GDIMAGE_PNG` option was removed); generated PNGs are byte-identical to before. symfony/yaml 8 needs PHP 8.4.1+; see the PHP minimum entry below.
- **Axios 1.21.0** – Bumped bundled `js/axios.min.js` (+ source map) from `1.11.0` to `1.21.0`, taken from the npm tarball and checked against its published sha512 integrity.
- **About → External libraries** – The About modal now lists third-party libraries with their versions, type (Frontend / PHP) and source in a table. `getAboutDependencies()` (`includes/about_info.php`) reads them from the files that load them — CDN tags in `index.php`, lazy loaders in `js/rand.js` (resolving `*_VERSION` constants), bundled `js/*.min.js` banners and Composer's `vendor/composer/installed.json` (falls back to `composer.lock`) — so versions stay in sync with no manual list. Rendered in `renderAboutInfo()` (`js/rand.js`).
- **Lucide icons** – Replaced Bootstrap Icons with the **Lucide** icon font (`lucide-static@1.66.0` CDN CSS) in `index.php` and `test_random_buttons.html`. Shared **`icon()`** helper now emits `icon-<name>` (with `aria-hidden`); every call site in `modules/`, `includes/`, `gen.php` and `js/rand.js` was renamed to its Lucide equivalent (e.g. `files`→`copy`, `arrow-repeat`→`refresh-cw`, `speedometer2`→`gauge`). Lucide has no brand marks, so GitHub uses `git-fork` and the browser inspector uses `app-window`. Base alignment rule for `[class^="icon-"]` added to `style.css`.
- **String Tools output** – Output is now a **read-only textarea** matching the input box instead of a green result card (`modules/string_tools.php`). `handle_stringtools` returns plain text (exact whitespace, HTML shown literally instead of rendered) and answers validation errors with HTTP 422, shown in a status area under the output. Shared **`copyToClipboard()`** (`index.php`) now copies the live `.value` of textarea/input targets.
- **Tabler 1.7.3** – Bumped `@tabler/core` CDN CSS/JS from `1.4.0` to `1.7.3` in `index.php` (and the `test_*.html` harness pages).
- **Shuffle-button fields** – Inputs/textareas wrapped in `.input-with-random-btn` no longer shrink to 500px while focused (global `.form-control:focus` cap out-ranked the wrapper's stretch rule in `style.css`). String Tools output textarea uses `rand-code-textarea` to span its column.
- **Syntax Highlighter** – New **Text & Data → Syntax Highlighter** tool (`modules/syntax_highlight.php`) using **highlight.js 11.12.0**: paste code, **auto-detect** language or override from the full language list (packs load in the background for better detection), **dark/light** theme toggle (persisted), live preview, **Copy plain** and **Copy rich (Word/OneNote)** (inline-styled HTML via clipboard; buttons sit in a top toolbar inside the preview). Shared CDN helpers in **`js/rand.js`** (`ensureHighlightJsAssets`, lazy language packs); Markdown asset loader bumped to the same highlight.js version.
- **Docker publish sync (Hub + GHCR)** – `docker-pushimage.sh` now builds once and `buildx --push`es the same tags to Docker Hub and GHCR; login/push failure on an enabled registry aborts (no more Hub-only “success” when GHCR was supposed to publish). GitLab CI docs note the fail-closed sync guarantee. Synced existing Hub → GHCR tags (`:latest` / `:v1.4.0` / `:1.4.0` identical digests; `:dev` / `:develop` identical config+layers) and added missing Hub `:1.4.0`.
- **Repo social preview** – Added GitHub-sized (`1280×640`) social cards in **`images/social-preview.png`** (dark) and **`images/social-preview-light.png`** (light); dark variant shown at the top of **`README.md`**.
- **Subnet Mask Calculator** – IP field accepts pasted CIDR (`10.0.0.0/20`); Bootstrap **`input-group`** with appended **`/prefix`** syncs bidirectionally with the subnet mask field. Server also accepts IP+CIDR when the subnet field is empty (`handle_ip_parse_ipv4_optional_cidr()`). Single shared shuffle button for the IP/prefix/mask block (`data-shared-random`); random samples vary the mask, not only `/24`.
- **CIDR to Range** – Result table now includes the dotted **Subnet mask** (e.g. `/20` → `255.255.240.0`), derived via existing `handle_ip_normalize_subnet_mask()`.
- **Module subnav (subpages)** – Multi-card tool pages now show a pill-style subnav bar that splits tools into individual subpages. Affected modules: **Networking** (DNS Lookup, CIDR→Range, Range→CIDR, Subnet Calculator, IP/Hex), **Date & Time** (Time Converter, Relative Time, Current Time), **Private/Public Keys** (Key Generator, Sign/Verify), **SSH Key Generator** (Generate, Verify). Active subtab is persisted in localStorage and deep-linkable via hash (e.g. `#networking/dns`). New CSS classes `.module-subnav`, `.subnav-pill`, `.subnav-panel`; JS helpers `initModuleSubnav()` / `activateSubtab()`; light-theme variant included.
- **Relative Time Calculator** – New **Date & Time → Relative Time** subpage (`#datetime/relative`) converts absolute dates/unix timestamps to human phrases (“3 hours ago”), evaluates relative expressions (`2 days ago`, `+3 hours`, `next Monday`) or amount+unit offsets into absolute times, and diffs two moments with a calendar breakdown. Handler **`handle_relative_time`**; helpers in **`includes/tooling_helpers.php`**; shuffle samples in **`js/rand.js`**.
- **SSH key verify** – Auto-detect of OpenSSH one-line public keys no longer fails with `preg_match(): Unknown modifier` when the Base64 blob contains `/` (fixed delimiter in `crypto_classify_verify_public_key()`).
- **Docker (GitLab CI)** – Image publish moved to **`gitlab01`** (`.gitlab-ci.yml`): push **`dev`** → `:dev`/`:develop`; push version tag → `:latest` + `:vX.Y.Z` (+ stripped) on Docker Hub + GHCR via **`docker-pushimage.sh`**. Local pre-push Docker hook removed; **`./scripts/install-git-hooks.sh`** clears old `core.hooksPath`. GitHub Actions Docker workflows stay unused. Local **`./docker-pushimage.sh`** remains as fallback.
- **Docker (local publish workflow)** – `./docker-pushimage.sh` modes: **`--dev`**, **`--release [vX.Y.Z]`**, **`--dry-run`**; env **`SKIP_DOCKERHUB`** / **`SKIP_GHCR`**. GHCR login failure no longer aborts after a successful Hub push. Docs updated in **`README.md`**, **`AGENTS.md`**, **`docker-image.config`**.
- **SSH / keypair PEM export** – Leaving the passphrase empty now exports a truly unencrypted private key (`BEGIN PRIVATE KEY`). Previously an empty string was passed to `openssl_pkey_export`, which produced `BEGIN ENCRYPTED PRIVATE KEY` with an empty password and caused `ssh-add` to prompt for a passphrase. Generate forms (SSH, keypair, CSR) now use an explicit **Protect private key with a passphrase** switch (off by default) via shared `crypto_passphrase_field_html()` / `crypto_req_optional_passphrase()`.
- **String Generator** – Added an **Alphanumeric** character-set option (`0-9a-zA-Z`). Default sets remain **Numbers**, **Lowercase**, and **Uppercase**; Alphanumeric is hidden and disabled when any set other than Numbers is selected. Shared `stringgen_charset_chars()` builds the pool for both generators; possible-combinations info now uses the real pool size.
- **Base Converter** – **Convert From** defaults to **Auto-detect** (still overridable). Shared `detect_convert_any_selector()` picks text / base64 / common numeric bases from the input; result heading shows the detected source. Also fixed “Invalid source/target format” for bases the UI already offered via `is_convert_any_selector()`.
- **Docker (local publish)** – `docker-pushimage.sh` supports `SKIP_DOCKERHUB=1` for GHCR-only pushes when GitHub Actions are disabled, falls back to `gh auth token` when `GITHUB_TOKEN` is unset, and publishes the stripped version tag (e.g. `1.4.0`) to match CI. GHCR success message now only lists tags that were actually pushed.
- **Docker (GHCR auth)** – Stale `GITHUB_TOKEN` in `.env.local` no longer blocks GHCR: login retries with `gh auth token` (via `env -u GITHUB_TOKEN`) and uses the live GitHub username for `docker login`.
- **Unit Converter (Data)** – Added SI bit units **kilobit (kb)**–**terabit (Tb)** and binary bit units **kibibit (Kib)**–**tebibit (Tib)**; From-unit select groups options as Bits/Bytes × decimal/binary via reusable `groups` + `<optgroup>` support (`modules/units.php`).
- **Calculator clipboard** – Keyboard-first copy/paste: **Ctrl/Cmd+C** copies the current result, **Ctrl/Cmd+V** pastes a number or valid expression (`+ - * /`, precedence-aware, no `eval`). Clicking the display also copies; invalid pastes show **Error**.
- **Crypto helpers (PHPStan)** – `crypto_signature_digest_for_key()` PHPDoc now accepts `OpenSSLAsymmetricKey|resource`, matching PHP 8+ OpenSSL key objects and sibling helpers.

---

## [v1.4.0] (2026-07-06)
### Major Features

- **Output typography** – Tool result text is larger app-wide via **`--rand-output-font-size`** (`1.2rem`) on **`.responseDiv`** and **`.copyable-content`**; redundant per-module inline output font sizes removed.
- **Copyable outputs** – Copy/action buttons sit on the **right** of output text (stacked for multi-action boxes); large split-pane outputs keep a footer bar with right-aligned actions.

- **Demo URL** – Centralized **`DEMO_URL`** (`https://rand.demo.roste.org/`) in **`includes/config.php`**; dashboard hero, About panel, and **`README.md`** point to the live demo host (replacing **`roste.org/rand`**).
- **About modal** – Navbar **About** opens a combined modal with **About** (php-rand version, Docker vs native, PHP version/SAPI, OS, server software, key extension status, full loaded extension list) and **Changelog** tabs (lazy-loaded on tab switch). Data from **`about.php`** / **`includes/about_info.php`**. Fixed stuck loading state: discard accidental PHP output in **`about.php`**, removed trailing **`?>`** from **`includes/functions.php`**, moved Bootstrap modal trigger to the About nav link, and hardened JSON parsing in **`js/rand.js`**.
- **String tools** – **Titlecase** / **Camelcase** use **`mb_convert_case`** so all-caps input title-cases correctly; **CRLF→LF**, **LF→CRLF**, and **Format** normalize real line endings (old **`crlf2lf`** stripped all breaks); **Custom characters** remove is implemented; **Regex** opens the Regex Tester with the current input; case tools use **`mb_*`** where appropriate.

- **Docker CI** – Fixed invalid `secrets` usage in step `if` conditions in **`.github/workflows/docker-release.yml`** and **`.github/workflows/docker-dev.yml`** by detecting Docker Hub credentials in a shell step and gating publish steps on `steps.dockerhub.outputs.enabled`.

- **Docker (rolling dev images)** – Pushes to the **`dev`** branch republish rolling **`dev`** and **`develop`** tags to Docker Hub and GHCR via **`.github/workflows/docker-dev.yml`** (no per-commit image tags). The **`Dockerfile`** copies the build context instead of cloning GitHub so CI images match the checked-out branch/tag; **`.dockerignore`** excludes local secrets and VCS metadata.

- **jQuery 4.0** – Upgraded CDN dependency from jQuery 3.7.1 to **4.0.0** (full build) in `index.php` and `test_random_buttons.html`; updated SRI hash. Migrate 4 audit (`test_jquery4_migrate_audit.html`) found no compatibility warnings across `js/rand.js` and modules with inline jQuery handlers.

- **Tool page descriptions** – Moved per-tool information alerts into each card as muted description text across tool modules (networking, units, crontab, serialization, shellcheck, validators, text/data tools, crypto, and others); dashboard “what’s new” banner unchanged.

- **Theme and UI scale preferences** – Navbar **Settings → Theme** (dark/light) and **Interface size** (compact through extra large) persisted via **`randUiPrefs`** (`js/rand_ui_boot.js`, **`includes/navbar.php`**); light-theme polish in **`style.css`**.
- **Global UI spacing preference + release guidance** – Navbar **Settings → Item spacing** (Tight/Dense/Standard/Comfortable/Relaxed) persisted via `randUiPrefs` (`spaceScale`) and applied app-wide in `style.css` by scaling common spacing utilities (`m-*`, `p-*`, `gap-*`, `g*/gx*/gy*`) plus card spacing. Added repository agent guidance in **`AGENTS.md`** covering core architecture, mandatory changelog policy, scripted release flow (`scripts/release.sh`, `scripts/extract_changelog_section.sh`, `scripts/update-release-descriptions.php`), and a quick release checklist. Also added an inline quick release checklist comment block at the top of `scripts/release.sh`.
- **Spin the wheel** – Per-item **weights** with optional **even distribution**; sector building supports **item slicing** for long lists; item rows honor the global spacing preference (`modules/spin_the_wheel.php`, **`handle_spinwheel()`**).
- **Clipboard UX** – Copy buttons reflect Clipboard API availability (disabled state + tooltip when copy is unavailable); complements the copyable-output layout refresh (`index.php`, **`style.css`**).

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
- **`style.css`** – Adjustments including Crontab / diff presentation, light theme, copy-button tooltips, and spacing scale utilities.

#### Spin the wheel
- **`modules/spin_the_wheel.php`** – Weight inputs, even-distribution toggle, weighted random selection, and canvas sector layout for large item sets.
- **`includes/handlers_functional.php`** – **`handle_spinwheel()`** returns weighted pick results aligned with client-side animation.

#### UI preferences
- **`js/rand_ui_boot.js`** – Early-load **`randUiPrefs`** (theme, `uiScale`, `spaceScale`) applied before paint.
- **`includes/navbar.php`** – Settings dropdown for theme, interface size, and item spacing.

#### Other
- **`modules/currency.php`**, **`modules/units.php`** – Minor copy or wiring updates where touched for consistency.

</details>

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
