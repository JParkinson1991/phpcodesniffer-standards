
# PHP_CodeSniffer Standards

This package provides custom and adapted [coding standards](#coding-standards) for use with [squizlabs/PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

The following standards are provided:

 - [JPSR-12](#coding-standard-jpsr-12)
	 - An adaptation of the PSR-12 coding standard

## Getting Started

Using the standards provided by this package is a simple as using composer to require within your project.

### Prerequisites

This package requires **squizlabs/php_codesniffer ^3.0** if you are using an older version of PHP_CodeSniffer you will not be able to install these standards.

### Installing

This package is built on top of [dealerdirect/phpcodesniffer-composer-installer](https://github.com/Dealerdirect/phpcodesniffer-composer-installer) so installation is as simple as

```
$ composer require jparkinson1991/phpcodesniffer-standards
```

After requiring this package in your project it will be installed and available for use with PHP_CodeSniffer

```
$ ./vendor/bin/phpcs -i
The installed coding standards are PEAR, Zend, PSR2, MySource, Squiz, PSR1, PSR12 and JPSR12
```

### Usage

Use the standards provided by this package in the exact same way you would use the defaults provided by PHP_Codesniffer. Using JPSR12 as an example.

```
$ ./vendor/bin/phpcs --standard JPSR12
```

```
// phpcs.xml
<rule ref="JPSR12"/>
```

## Coding Standard: JPSR-12

An adaptation of the [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard with some minor tweaks.

### Rules

The following documents notable rules where deviated from the PSR-12 standard. Source can be inspected for full ruleset.

#### Array Rules
```
<?php
/**
 * No space in empty array definitions
 */

// Invalid
$test = array( );
$test = [ ];

// Valid
$test = array();
$test = [];
```
```
<?php
/**
 * No spaces allowed before comma
 */

// Invalid
$test = ['item' , 'another item'];
$test = [
    'item' ,
    'two'
];

// Valid
$test = ['item', 'another item'];
$test = [
    'item',
    'two'
];
```
```
<?php
/**
 * No trailing commas
 */

// Invalid
$test = ['one', 'two',];
$test = [
    'one',
    'two',
];

// Valid
$test = ['one', 'two'];
$test = [
    'one',
    'two'
];
```
#### Control Structure Rules
```
<?php
/**
 * Enfore new line with multi keyword structures
 *
 * Example below stands true for: catch, else, elseif
 */

// Invalid
if (true) {

} else {

}

// Valid
if (true) {

}
else {

}
```

#### Operator Rules

```
<?php
/**
 * Enforce operator spacing except for concatenation
 */

// Invalid
$test=1+1;
$test = 1-2;
$test = 'start' . $concat . 'end';

// Valid
$test = 1 + 1;
$test = 1 - 2;
$test = 'start'.$concat.'end';
```

## Versioning

[SemVer](http://semver.org/) is used for versioning. For the versions available, see the [tags on this repository](https://github.com/JParkinson1991/phpcodesniffer-standards/tags).

## Authors

* **Josh Parkinson** - Hacking and slashing

## License

This project is licensed under the GNU GPLv3 License - see the [LICENSE](LICENSE)
file for details

## Acknowledgments

* [dealerdirect](https://github.com/Dealerdirect) *For the coding standards installer plugin*
* [Drupal](https://www.drupal.org/) *For the multi keyword control structure sniff*
* [PHP-FIG](https://www.php-fig.org) *For PSR-12*
* [squizlabs](https://github.com/squizlabs)  *For PHP_Codesniffer*
