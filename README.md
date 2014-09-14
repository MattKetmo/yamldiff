# YamlDiff

[![Build status...](https://img.shields.io/travis/MattKetmo/yamldiff.svg?style=flat)](http://travis-ci.org/MattKetmo/yamldiff)
[![Code quality...](https://img.shields.io/scrutinizer/g/MattKetmo/yamldiff.svg?style=flat)](https://scrutinizer-ci.com/g/MattKetmo/yamldiff/)
[![License MIT](http://img.shields.io/badge/license-MIT-blue.svg?style=flat)](https://github.com/MattKetmo/yamldiff/blob/master/LICENSE)
[![Packagist](http://img.shields.io/github/tag/MattKetmo/yamldiff.svg?style=flat)](https://packagist.org/packages/mattketmo/email-checker)

Spot the differences between 2 yaml files.

## Installation

Download the compiled command:

```shell
curl -LO https://github.com/downloads/MattKetmo/yamldiff/yamldiff.phar
```

Or download the sources and compile it manually:

```shell
git clone git://github.com/MattKetmo/yamldiff.git
cd yamldiff/
curl -s https://getcomposer.org/installer | php
php composer.phar install
./bin/compile
```

Then move it to your `$PATH`:

```shell
chmod +x yamldiff.phar
mv yamldiff.phar /usr/local/bin/yamldiff
```

## Usage

```shell
yamldiff file1.yml file2.yml
```

This will show the keys which are present on `file1.yml` and missing on
`file2.yml` (prefixed by a `+`), and vice versa (prefixed by a `-`).

For instance, this can be really usefull when comparing your local file
`parameters.yml` with the versionned one `parameters.yml.dist` on
a [Symfony](http://symfony.com) project:

```shell
yamldiff app/config/parameters.yml.dist app/config/parameters.yml
```

## License

YamlDiff is released under the MIT License.
See the [bundled LICENSE file](LICENSE) for details.
