# phprand

A set of useful tools for developers.

![Rand](images/image.png)

# features
* Generators
  * String generator
  * Number generator
  * Logo generator (https://github.com/Darknetzz/php-logogen)
  * Spin the wheel
* Encoding / Decoding
  * Base converters
  * Bin2Hex, Hex2Bin
  * URL encoding/decoding
  * HTML entities encode/decode
  * Hashing: SHA512, SHA256, SHA1, MD5
* Encryption / Decryption
  * OpenSSL
  * ROT
* Networking
  * CIDR to range
  * Range to CIDR
  * Subnet mask
  * IP2Hex, Hex2IP
* Serialization
  * JSON, YAML and XML
* Misc.
  * String tools - trim, reverse, shuffle, convert etc.
  * Datetime tools
  * Calculator

# install
Simply clone the repo and put it on your webserver with PHP support.

```bash
# install dependencies
sudo apt install -y php8.3-openssl php8.3-mbstring #...

# Assuming your webroot is located in `/var/www/html`
cd /var/www/html

# Clone the repo
git clone https://github.com/Darknetzz/php-rand.git
```

# demo
* demo @ https://roste.org/rand
