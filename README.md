# YamlDiff

Spot the differences between 2 yaml files.

## Installation

Download the compiled command:

    curl -LO https://github.com/downloads/MattKetmo/yamldiff/yamldiff.phar

Or download the sources and compile it manually:

    git git://github.com/MattKetmo/yamldiff.git
    cd yamldiff/
    curl -s https://getcomposer.org/installer | php
    php composer.phar install
    ./bin/compile

Then move it to your `$PATH`:

    chmod +x yamldiff.phar
    mv yamldiff.phar /usr/local/bin/yamldiff

## Usage

    yamldiff file1.yml file2.yml

This will show the keys which are present on `file1.yml` and missing on
`file2.yml` (prefixed by a `+`), and vice versa (prefixed by a `-`).

For instance, this can be really usefull when comparing your local file
`parameters.yml` with the versionned one `parameters.yml.dist` on
a [Symfony](http://symfony.com) project:

    yamldiff app/config/parameters.yml.dist app/config/parameters.yml
