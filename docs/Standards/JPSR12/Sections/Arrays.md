# [JPSR-12](../JPSR12.md): Arrays

## Prefix

Array rules are pulled directly for the [Squiz standard](https://github.com/squizlabs/PHP_CodeSniffer/blob/master/src/Standards/Squiz/Sniffs/Arrays/ArrayDeclarationSniff.php) with the exception of the following:

 - `Squiz.Arrays.ArrayDeclaration.NoComma`
 - `Squiz.Arrays.ArrayDeclaration.NoCommaAfterLast`
 - `Squiz.Arrays.ArrayDeclaration.SingleLineNotAllowed`
 - `Squiz.Arrays.ArrayDeclaration.DoubleArrowNotAligned`
 - `Squiz.Arrays.ArrayDeclaration.ValueNotAligned`
 - `Squiz.Arrays.ArrayDeclaration.KeyNotAligned`
 - `Squiz.Arrays.ArrayDeclaration.CloseBraceNotAligned`


## List

- [Alignment](#alignment)
- [Last Element Comma](#last-element-comma)
- [Long Syntax](#long-syntax)
- [Single Line Multi Value Arrays](#single-line-multi-value-arrays)

## Rules

> Deviations from Squiz rules defined here.

### Alignment

Array alignment must follow standard indentation. There **must not be** any column alignment of array values, keys, double arrows or braces.

#### Example

```
<?php

$array = [
    'key' => 'value',
    'another key' => 'another value'
];
```

### Last Element Comma
The last element in an array **must not** end in a comma.

#### Example

```
<?php

$array = ['one', 'two'];
$array = [
    'one',
    'two'
];
```

### Long Syntax

The use of the long array syntax is **disallowed**

#### Example

```
<?php
//Valid
$array = ['one', 'two'];
// Error
$array = array('one', 'two');
```

### Single Line Multi Value Arrays

The use of multiple values in a single line array declaration is **allowed**.

*Remembering, arrays must have all values on a single line or one value per line. There can not be mutliple lines with multiple values on them.*

#### Example

```
<?php
//Valid
$array = ['one', 'two'];
$array = ['key' => 'pair', 'another key' => 'pair'];

// Invalid
$array = ['one', 'two',
    'three', 'four'];
$array = ['key' => 'pair',
    'another key' => 'pair'];

```
