# [JPSR-12](../JPSR12.md): Types

## List

- [Strict Types](#strict-types)

## Rules

### Strict Types

The use of `declare(strict_types=1)` is **required** in all `.php` files of types
that have not been defined as exclusion.

The use of `declare(strict_types=n)` where `n` **is not** `1` will be treat as
an error.

#### Exclusions

The use of `declare(strict_types=n)` is prohibited for the following:

 - Files defining interfaces

#### Example

##### Valid Declaration

```
<?php
/**
 * @file SomeClass.php
 */

declare(strict_types=1)

class SomeClass
{
    ...
}
````

##### Invalid Declaration

```
<?php
/**
 * @file SomeClass.php
 */

declare(strict_types=0)

class SomeClass
{
    ...
}
````

##### Invalid Interface Declaration

```
<?php
/**
 * @file SomeInterface.php
 */

declare(strict_types=1)
// or declare(strict_types=0)

interface SomeInterface
{
    ...
}
````
