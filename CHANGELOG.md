# Changelog

All notable changes to this project are documented in this file.

---

## [v1.2.7] (2026-03-19)

### Major Features
- **Unit Converter** (Convert → Units) – Single page with 12 measurement categories: Volume, Length, Weight & mass, Temperature, Energy, Area, Speed, Time, Power, Data, Pressure, Angle. Enter a value and source unit; result is shown as a table of equivalent values in all other units for that category (client-side). Currency remains a separate module (Convert → Currency Converter).
- **Calculator** (Miscellaneous) – Basic arithmetic calculator with safe math evaluation (no `eval`); supports expressions such as `25+8*3`, parentheses, and common operators.

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### Unit Converter
- **Convert → Units** – New nav item under Convert; tabbed interface for all unit types
- **Categories** – Volume (L, mL, gallon, quart, pint, cup, fl oz, m³, ft³, in³), Length (m, km, cm, mm, mile, yard, foot, inch, nautical mile), Weight & mass (kg, g, mg, lb, oz, ton metric/US), Temperature (C, F, K), Energy (J, kJ, cal, kcal, kWh, eV, BTU), Area (m², km², ft², in², hectare, acre), Speed (m/s, km/h, mph, knot, ft/s), Time (s, min, h, day, week, month, year), Power (W, kW, hp metric/US, BTU/h), Data (bit, byte, KB–TB, KiB–TiB), Pressure (Pa, kPa, bar, psi, atm, mmHg, inHg), Angle (deg, rad, grad, arcmin, arcsec)
- **UX** – One “From unit” dropdown per category; convert to all other units in a copyable table
- **Currency** – No duplicate; currency conversion stays on the dedicated Convert → Currency Converter page

#### Calculator
- **Miscellaneous menu** – Calculator module for basic arithmetic expressions
- **Safe evaluation** – Uses tokenization and operator precedence (no `eval`); supports +, −, ×, ÷, parentheses, and common math

</details>

---

## [v1.2.6] (2026-03-13)

### Major Features
- **Number Generator** – Generate multiple numbers at once (1–500), configurable separator (comma, newline, tab, pipe, custom), and custom seed fix
- **Docker** – Container prints php-rand version on start; build uses `PHP_RAND_VERSION` arg
- **Secrets** – Prefer `.env.local` for Docker push secrets so they survive git pull/merge

<details>
<summary>📋 Detailed Changes (click to expand)</summary>

#### Number Generator
- **Quantity** – Generate 1–500 numbers per run; output joined with chosen separator
- **Separator** – Presets: Comma and space, Newline, Tab, Space, Pipe; or Custom (free text, max 20 chars). Newline/tab display correctly in copyable output (`white-space: pre-wrap`)
- **Seed fix** – Checkbox renamed to `numgenuseseed` so it is no longer overwritten by a hidden field; seed applied once for multiple numbers (reproducible sequence)
- **Copyable output** – Shared copyable div now uses `white-space: pre-wrap` so newline-separated content displays and copies correctly

#### Docker
- **Entrypoint** – Prints `php-rand &lt;version&gt;` to console on container start; version set via `--build-arg PHP_RAND_VERSION=$VERSION` in `docker-pushimage.sh`
- **Secrets** – Script sources `.env.local` first, then `.env`; `.env.local` added to `.gitignore` so it is never removed by merges that deleted `.env` from repo history

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
