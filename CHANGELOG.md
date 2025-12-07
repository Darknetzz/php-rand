## **v1.2.2** (2025-12-07)

### 🎉 Major Features
- **Global UTF-8 encoding** - Consistent UTF-8 across all modules
- **Complete PHPDoc documentation** - 30+ functions fully documented
- **Context-aware random data** - Smart sample data based on field type
- **Input validation framework** - Security hardening with DOS prevention

<details>
<summary><strong>📋 Detailed Changes</strong> (click to expand)</summary>

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
- Length limits: 100K-1M chars depending on handler
- Range validation, whitelist checking, consistent error messages

#### Other Changes
- Networking outputs now render clean HTML
- README updated with DNS lookup and enhanced tools
- Validation simplified to support optional fields

</details>

---

## **v1.2.1**

### 🎉 Major Features
- **Random Data Buttons** - Auto-generate contextual sample data for all inputs
- **Dashboard redesign** - Modern UI with stats, categories, and feature highlights

<details>
<summary><strong>📋 Detailed Changes</strong> (click to expand)</summary>

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

## **v1.2.0**

### 🎉 Major Features
- **UI/UX Modernization** - Complete redesign of all 15+ modules
- **Pure PHP diff** - No xdiff extension required
- **Copyable outputs** - Consistent styled output boxes everywhere

<details>
<summary><strong>📋 Detailed Changes</strong> (click to expand)</summary>

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

## **v1.1.1**

### 🎉 Major Features
- **New modules**: Metaphone, Levenshtein, Diff, Currency converter
- **Networking**: DNS lookup tool

<details>
<summary><strong>📋 Detailed Changes</strong> (click to expand)</summary>

- Module: metaphone (phonetic key generation)
- Module: levenshtein (string distance)
- Module: diff (text comparison)
- Module: currency converter
- Network tool: DNS lookup
- String generator: info output now in table format
- String tools: output to textbox disabled by default

</details>

## **v1.1.0**

### 🎉 Major Features
- **New encoding tools**: URL encoding, HTML entities
- **New converters**: Markdown, Minify
- **Spin the wheel** - Now an actual wheel animation

<details>
<summary><strong>📋 Detailed Changes</strong> (click to expand)</summary>

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

## **v1.0.2**

### 🎉 Major Features
- **Calculator module** added

## **v1.0.1**

### 🎉 Major Features
- **Logogen submodule** added

## **v1.0.0**

### 🎉 Major Features
- **Changelog modal** with marked.js
- **Updated dependencies**: Tabler v1.4.0, Highlight.js v11.11.1

<details>
<summary><strong>📋 Detailed Changes</strong> (click to expand)</summary>

- Changelog modal implementation
- Marked.js for markdown rendering
- Tabler updated to v1.4.0
- Highlight.js updated to v11.11.1
- Modal background opacity and color adjusted
- Fixed href on nav changelog button

</details>