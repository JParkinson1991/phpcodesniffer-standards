# [JPSR-12](../JPSR12.md): Operators

## List

- [Spacing](#spacing)

## Rules

### Spacing

Spacing rules exist as expected within the PSR-12 standard with one exception.

### String Concatenation

There **can not** be a spaces around the string concatenation operator.

#### Example

```
<?php
// Valid
$string = 'concatenate'.$variable;
$string = 'concatenate'.$variable.'again';

// Invalid
$string = 'concatenate' . $variable;
$string = 'concatenate' . $variable . 'again';
```

**Single newlines are allowed** before and after the string concatenation operator.

#### Example

```
// Valid (new line allowed before and after operator)
$string = 'concatenate'.
    $variable
    .'again';

// Invalid (blank lines & space after last operator)
$string = 'concatenate'.

    $variable


    . 'again';
```

When auto fixing new line errors, string concatenation operators will be moved to the start of all fixed lines.

#### Example

```
// Invalid source
$string = 'concatenate'.

    $variable


    . 'again';

// Autofix result
$string = 'concatenate'
    .$variable
    .'again';
```
