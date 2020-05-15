# [JPSR-12](../JPSR12.md): Operators

## List

- [Spacing](#spacing)

## Rules

### Spacing

Spacing rules exist as expected within the PSR-12 standard with one exception.

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
