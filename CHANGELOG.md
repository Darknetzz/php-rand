## **v1.2.1**
> ### new
> * **Random Data Buttons** - automatic random data generation buttons for all input fields
>   * Smart context detection based on placeholder text (email, URL, IP, hex, JSON, etc.)
>   * Visual feedback with shuffle icon and success checkmark
>   * Works automatically across all modules without configuration
>   * Generates contextual data: emails, URLs, IPs, hex hashes, base64, JSON, code snippets, lorem ipsum
> * **Dashboard redesign** - complete modernization with improved UX
>   * Hero section with call-to-action buttons
>   * Stats dashboard showing tool counts by category
>   * Feature highlights showcasing smart random data and key benefits
>   * Color-coded tool categories with organized navigation
>   * Fun facts section with educational content
>   * Improved disclaimer styling
> ### changed
> * dashboard: complete UI overhaul with gradient backgrounds, stat cards, and better organization
> * dashboard: added visual stats (total tools, crypto tools, generators, encoders)
> * dashboard: improved navigation with color-coded categories (generators: teal, cryptography: blue, encoding: purple, convert: orange, misc: pink)
> * dashboard: new feature alert banner for v1.2.1 random data buttons

## **v1.2.0**
> ### new
> * global `copyableOutput()` function for styled output boxes with copy buttons
> * global `copyToClipboard()` JavaScript function with Clipboard API and document.execCommand fallback
> * timezone selector in datetime module
> * pure PHP diff algorithm (no xdiff extension required)
> ### changed
> * **UI/UX Modernization** (all modules below):
>   * openssl: complete redesign with amber gradient, security alert, encryption settings card
>   * hash: multi-algorithm display with individual copy buttons, teal/blue gradient output
>   * gen_number: large input styling (1.5rem), seed toggle, copyable results
>   * gen_string: split layout, character badges, multiple copyable results
>   * base: cleaner output format with directional header
>   * binhex: purple gradient, directional toggle buttons
>   * rot: fixed bruteforce toggle, multi-rotation display with ROT labels
>   * htmlentities: split output into original/encoded/decoded with copy buttons
>   * urlencoding: triple output (original/encoded/decoded) with copyable boxes
>   * string_tools: purple gradient panel, organized tool buttons, copy output
>   * serialization: cyan/blue gradient, textarea inputs, JSON focus, detected format label
>   * markdown: live preview with purple gradient, HTML code block copy button, debounced auto-render
>   * minify: amber/orange gradient, compression stats display (original size, minified size, bytes saved, %)
>   * metaphone: teal gradient output, copyable phonetic key
>   * datetime: removed WIP time calculator, simplified current time display with timezone selector, modern time unit converter
>   * networking: complete redesign with split input/output layouts, 5 modernized tools (DNS lookup, CIDR to range, range to CIDR, subnet calculator, IP/hex converter) with gradient backgrounds and responsive grids
>   * levenshtein: split layout, large textareas (200px), cost inputs in responsive grid, orange gradient output with prominent distance display
>   * diff: unified diff viewer with side-by-side input layout, purple gradient output, improved error handling, works without xdiff extension
> * all module outputs now use consistent copyableOutput() styling with dark theme optimization
> * all modules updated with split input/output layouts (col-12 col-lg-6 responsive grid)
> * all module outputs include gradient backgrounds themed by module purpose
> * all form controls upgraded to form-select-lg/form-control-lg for better visibility
> * all outputs include empty state with centered icon and placeholder text
> * all info alerts now have consistent ℹ️ icon styling
> ### fixed
> * rot: bruteforce toggle now properly syncs with form submission
> * minify: backend now calls .minify() method on minifier objects (was returning objects)
> * datetime: unit key inconsistency fixed (months now use "M" consistently)
> * markdown: alert text alignment fixed with inline-block display
> * copy-to-clipboard: now works on both HTTP and HTTPS origins with fallback support
> * output visibility: improved contrast on dark theme with #0f172a backgrounds and #e9ecef text

## **v1.1.1**
> ### new
> * module: metaphone
> * module: levenshtein
> * module: diff
> * module: currency converter
> * network: dns lookup
> ### changed
> * genstr: info output in table
> * strtools: output to textbox is now disabled by default

## **v1.1.0**
> ### new
> * encoding: urlencoding
> * encoding: htmlentities
> * convert: markdown
> * convert: minify (coming soon)
> ### changed
> * spin the wheel: is now an actual wheel
> * included files are moved to `includes` directory
> * renamed some modules
> * navbar: reworked and reorganized to simplify gathering modules into dropdowns
> * dashboard: moved disclaimer to top of page
> * serialization: moved from misc to convert
> ### fixed
> * navbar: removed duplicate hash

## **v1.0.2**
> ### new
> * calculator

## **v.1.0.1**
> ### new
> * logogen submodule


## **v1.0.0**
> ### new
> * changelog modal
> * marked.js
> ### updated
> * tabler to v1.4.0
> * highlight.js to v11.11.1
> * changed opacity and color of modal-background
> ### fixed
> * href on nav changelog button