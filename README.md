# Cyr-To-Lat

Converts Cyrillic characters in post, page and term slugs to Latin characters. Useful for creating human-readable URLs.

![](./assets/banner-772x250.png)

## Features

* Converts any number of existing post, page and term slugs in background processes
* Saves existing post and page permalinks integrity
* Performs transliteration of attachment file names
* Includes Russian, Belorussian, Ukrainian, Bulgarian, Macedonian, Georgian, and Kazakh characters
* Transliteration table can be customized without editing the plugin by itself

## Installation

```
git clone https://github.com/mihdan/cyr2lat.git
cd cyr2lat
composer install --no-dev
cd src
yarn
yarn run build:prod
```

## Development

```
git clone https://github.com/mihdan/cyr2lat.git
cd cyr2lat
composer install
cd src
yarn
yarn run build:dev
```

## WP-CLI support

```
wp cyr2lat regenerate [--post_type=<post_type>] [--post_status=<post_status>]
```

Where
  `-post_type` is list of post types,
  `-post_status` is list of post statuses.

## Packagist

[![Total Downloads](https://poser.pugx.org/mihdan/cyr2lat/downloads)](https://packagist.org/packages/mihdan/cyr2lat)

[![License](https://poser.pugx.org/mihdan/cyr2lat/license)](https://packagist.org/packages/mihdan/cyr2lat)

[![Latest Stable Version](https://poser.pugx.org/mihdan/cyr2lat/v/stable)](https://packagist.org/packages/mihdan/cyr2lat)

[![Latest Unstable Version](https://poser.pugx.org/mihdan/cyr2lat/v/unstable)](https://packagist.org/packages/mihdan/cyr2lat)

[![composer.lock](https://poser.pugx.org/mihdan/cyr2lat/composerlock)](https://packagist.org/packages/mihdan/cyr2lat)

## License

The WordPress Plugin Cyr-To-Lat is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE`.

## Credits

The current version of the Cyr-To-Lat was developed by Sergey Biryukov, Mikhail Kobzarev, and Igor Gergel.

Contributors: [SergeyBiryukov](https://github.com/SergeyBiryukov), [mihdan](https://github.com/mihdan), [karevn](https://github.com/karevn), [webvitaly](https://github.com/webvitaly), [kagg-design](https://github.com/kagg-design).