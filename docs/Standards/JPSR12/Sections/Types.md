# [JPSR-12](../JPSR12.md): Types

## List

- [Strict Types](#strict-types)

## Rules

### Strict Types

The use of `declare(strict_types=1)` is **required** in all `.php` files.

#### Example

```
<?php
/**
 * @file SomeClass.php
 */

declare(strict_types=1)

/**
 * Some class, does some stuff
 */
class SomeClass
{

}
````
