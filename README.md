# phprand

A comprehensive collection of useful developer tools built with PHP and modern web technologies.

**Demo:** [https://roste.org/rand](https://rand.demo.roste.org/)

> [!WARNING]  
> Disclaimer: Please do not host this tool on a publicly accessible server. It most likely contains security vulnerabilities.

![Rand](images/image2.png)

## Features

### Generators
- String generator
- Number generator
- QR Code generator
- Logo generator ([php-logogen](https://github.com/Darknetzz/php-logogen))
- Spin the wheel

### Encoding & Decoding
- Base converters
- Bin2Hex, Hex2Bin
- URL encoding/decoding
- HTML entities encode/decode

### Encryption & Hashing
- OpenSSL (AES encryption/decryption)
- Hashing (SHA512, SHA256, SHA1, MD5, and more)
- ROT Cipher

### Conversion & Transformation
- String tools (trim, reverse, shuffle, case conversion, etc.)
- Serialization (JSON, YAML, XML)
- Markdown editor (client-side live preview)
- Minify (CSS and JavaScript)
- Metaphone (phonetic key generation)
- Unit converter (Convert → Units: volume, length, weight, temperature, energy, area, speed, time, power, data, pressure, angle)
- Currency converter (Convert → Currency Converter)

### Networking
- DNS lookup (hostname/IP resolution)
- CIDR to IP range
- IP range to CIDR conversion
- Subnet mask calculator
- IP to hexadecimal converter

### Comparison & Analysis
- Levenshtein distance (with tunable costs)
- Diff viewer (pure PHP, colorized output)

### Miscellaneous
- Calculator (basic arithmetic)
- Datetime tools (time unit conversion, timezone selector)

## Installation

### Docker (Recommended)

#### Pull from Docker Hub
```bash
# Pull latest image
docker pull darknetz/php-rand:latest

# Run container (replace 12345 with your desired port)
docker run -d -p 12345:80 --name php-rand darknetz/php-rand:latest
```

#### Docker Compose
```yaml
services:
  phprand:
    image: darknetz/php-rand:latest
    container_name: php-rand
    ports:
      - "12345:80"  # replace 12345 with your desired port
    restart: unless-stopped
```

#### Build from Dockerfile
```bash
# Clone repo
git clone --recurse-submodules https://github.com/Darknetzz/php-rand.git && cd php-rand

# Build image
docker build --no-cache -t php-rand .

# Run container (replace 12345 with your desired port)
docker run -d -p 12345:80 --name php-rand php-rand
```

### Manual Installation (Without Docker)

Requirements:
- PHP 8.3+ with extensions: mbstring, mcrypt, gd, yaml, xml
- Web server (Apache, Nginx, etc.) with PHP support
- Composer (for dependency management)

```bash
# Install system dependencies (Ubuntu/Debian)
sudo apt install -y php8.3-common php8.3-mbstring php8.3-mcrypt php8.3-gd php8.3-yaml php8.3-xml

# Clone repository (assuming webroot is /var/www/html)
cd /var/www/html
git clone --recurse-submodules https://github.com/Darknetzz/php-rand.git
cd php-rand

# Install Composer dependencies
composer install
```

Open your browser and visit `http://<webserver>/php-rand`

## Production Performance Guidance

For production deployments, enable HTTP compression and strong cache headers for static assets.

### Nginx (gzip + brotli + cache headers)

```nginx
# Compression
gzip on;
gzip_comp_level 5;
gzip_min_length 1024;
gzip_vary on;
gzip_proxied any;
gzip_types text/plain text/css application/javascript application/json application/xml image/svg+xml;

# If ngx_brotli is installed:
brotli on;
brotli_comp_level 5;
brotli_types text/plain text/css application/javascript application/json application/xml image/svg+xml;

# Static assets: long cache
location ~* \.(?:css|js|png|jpg|jpeg|gif|svg|webp|ico|woff2?)$ {
    expires 30d;
    add_header Cache-Control "public, max-age=2592000, immutable";
}

# HTML/PHP responses: short/no cache
location ~* \.(?:html|php)$ {
    add_header Cache-Control "no-cache, must-revalidate";
}
```

### Apache (.htaccess or vhost)

```apache
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/plain text/html text/css application/javascript application/json application/xml image/svg+xml
</IfModule>

<IfModule mod_brotli.c>
  AddOutputFilterByType BROTLI_COMPRESS text/plain text/html text/css application/javascript application/json application/xml image/svg+xml
</IfModule>

<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType text/css "access plus 30 days"
  ExpiresByType application/javascript "access plus 30 days"
  ExpiresByType image/png "access plus 30 days"
  ExpiresByType image/jpeg "access plus 30 days"
  ExpiresByType image/svg+xml "access plus 30 days"
</IfModule>

<IfModule mod_headers.c>
  <FilesMatch "\.(css|js|png|jpg|jpeg|gif|svg|webp|ico|woff|woff2)$">
    Header set Cache-Control "public, max-age=2592000, immutable"
  </FilesMatch>
  <FilesMatch "\.(php|html)$">
    Header set Cache-Control "no-cache, must-revalidate"
  </FilesMatch>
</IfModule>
```

Notes:
- Use content-hashed filenames (`app.abc123.js`) for truly immutable caching.
- Keep HTML dynamic/short-lived so new releases are discovered quickly.

## Documentation

For detailed documentation, feature guides, and implementation details, see:
- [CHANGELOG](docs/CHANGELOG.md) - Version history and feature updates
- [README](docs/README.md) - Detailed feature documentation
- [IMPLEMENTATION_SUMMARY](docs/IMPLEMENTATION_SUMMARY.md) - Technical implementation details
- [VISUAL_GUIDE](docs/VISUAL_GUIDE.md) - UI/UX design documentation
- [RANDOM_BUTTON_FEATURE](docs/RANDOM_BUTTON_FEATURE.md) - Random data generation feature guide

## Release Workflow

Use the interactive release helper:

```bash
./scripts/release.sh 1.2.9
```

What it does:
- rotates `CHANGELOG.md` from `## [Unreleased]` into `## [vX.Y.Z] (YYYY-MM-DD)`
- recreates a fresh `Unreleased` template
- creates a release commit and annotated tag (`vX.Y.Z`)
- optionally pushes branch + tag after an explicit confirmation prompt
- optionally creates a GitHub release via `gh`
- when a `v*` tag is pushed, GitHub Actions automatically publishes Docker images

Environment toggles:
- `PUSH_REMOTE=1` to pre-enable push flow (still requires confirmation)
- `REMOTE_NAME=origin` to set the push remote
- `CREATE_GH_RELEASE=1` to auto-create GitHub release via `gh`
- `PUBLISH_DOCKER=1` to include Docker publish prompt (`VERSION_OVERRIDE=vX.Y.Z ./docker-pushimage.sh`)

Common options:
- `./scripts/release.sh --help` to show usage
- `./scripts/release.sh 1.2.9 --dry-run` to preview actions only
- `./scripts/extract_changelog_section.sh --help` to show extraction script usage

CI Docker publish requirements:
- Docker Hub secrets: `DOCKERHUB_USERNAME` and `DOCKERHUB_TOKEN`
- GHCR publish uses `GITHUB_TOKEN` automatically

## Key Features

### 🎲 Smart Random Data Generation
Automatic random data buttons for all input fields with context-aware detection:
- Calculator inputs get math expressions
- Networking fields get IP addresses or CIDR notation
- Email fields get email addresses
- And 20+ other context-specific generators

### 🌍 UTF-8 Support
Full UTF-8 character encoding support across all modules for international text handling.

### 📚 Comprehensive Documentation
All functions include PHPDoc comments with descriptions, parameters, return types, and usage examples.

### 🎨 Modern UI/UX
- Dark theme with gradient accents
- Responsive Bootstrap-based layout
- Split input/output designs
- Smooth animations and visual feedback
- Copy-to-clipboard functionality

## Architecture

- **Backend:** PHP 8.3+ with modular design
- **Frontend:** Vanilla JavaScript, jQuery, Bootstrap 5, Tabler UI
- **Dependencies:** Composer-managed (minify utilities, submodules)
- **Extensible:** Easy to add new tools by creating new modules

## License

See LICENSE file in the repository.

## Support

For bugs, feature requests, or questions, please visit the [GitHub repository](https://github.com/Darknetzz/php-rand).
