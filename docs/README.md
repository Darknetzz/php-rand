# phprand

A set of useful tools for developers.

Demo: https://roste.org/rand


> [!WARNING]  
> Disclaimer: Please do not host this tool on a publicly server. It most likely contains a bunch of security holes.


![Rand](images/image2.png)

# features
* Generators
  * String generator
  * Number generator
  * Logo generator ([php-logogen](https://github.com/Darknetzz/php-logogen))
  * Spin the wheel
* Encoding / Decoding
  * Base converters
  * Bin2Hex, Hex2Bin
  * URL encoding/decoding
  * HTML entities encode/decode
* Encryption / Decryption
  * OpenSSL
  * Hashing (SHA512, SHA256, SHA1, MD5)
  * ROT Cipher
* Convert
  * String tools - trim, reverse, shuffle, convert etc.
  * Serialization (JSON, YAML, XML)
  * Markdown editor (client-side)
  * Minify (WIP...)
  * Metaphone
* Networking
  * DNS lookup (hostname/IP)
  * CIDR to range
  * Range to CIDR
  * Subnet mask
  * IP2Hex, Hex2IP
* Misc.
  * Serialization (JSON, YAML, XML)
  * Datetime tools
  * Calculator
  * Levenshtein distance (tunable costs)
  * Diff viewer (pure PHP, colorized)

# install

## docker pull
```bash
# pull latest image
docker pull darknetz/php-rand:latest

# run container (replace 12345 with your desired port)
docker run -d -p 12345:80 --name php-rand darknetz/php-rand:latest
```

## docker compose
```yaml
services:
  phprand:
    image: darknetz/php-rand:latest
    container_name: php-rand
    ports:
      - "12345:80"  # replace 12345 with your desired port
    restart: unless-stopped
```

## dockerfile build and run
```bash
# clone repo
git clone --recurse-submodules https://github.com/Darknetzz/php-rand.git && cd php-rand

# build image
docker build --no-cache -t php-rand .

# run container (replace 12345 with your desired port)
docker run -d -p 12345:80 --name php-rand php-rand
```

## manual installation (without docker)
If you want to run this on your own webserver,
simply clone the repo and put it on your webserver with PHP support.

```bash
# install dependencies
sudo apt install -y php8.3-common php8.3-mbstring php8.3-mcrypt php8.3-gd php8.3-mcrypt php8.3-yaml php8.3-xml

# Assuming your webroot is located in `/var/www/html`
cd /var/www/html

# Clone the repo
git clone --recurse-submodules https://github.com/Darknetzz/php-rand.git

# Get the Composer dependencies (https://getcomposer.org/download/)
cd php-rand
composer install
```

Open a webbrowser and visit `http://<webserver>/php-rand`
