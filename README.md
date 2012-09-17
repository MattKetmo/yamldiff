# YamlDiff

Spot the differences between 2 yaml files.

## Installation

Via composer:

```
curl -s https://getcomposer.org/installer | php
php composer.phar install
```

Compile the project into an single phar instance:

```
./bin/compile
chmod +x yamldiff.phar
mv yamldiff.phar /usr/local/bin/yamldiff
```

## Usage

```
yamldiff file1.yml file2.yml
```

This will show the keys which are present on `file1.yml` and missing on
'file2.yml' (prefixed by a '+'), and vice versa (prefixed by a '-').

For instance, this can be really usefull when comparing your local file
`parameters.yml` with the versionned one `parameters.yml.dist` on
a [Symfony](http://symfony.com) project.
