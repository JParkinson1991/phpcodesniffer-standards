# [JPSR-12](../JPSR12.md): Files

## List

- [Blank Lines](#blank-lines)
- [File Header](#file-header)


## Rules

### Blank Lines

Multiple blank lines **are not allowed** between code or docblock contents.

#### Examples

##### Bad

```
<?php
$code = true;


$anotherLine = 'here';
```
```
<?php
/**
 * This is a docblock
 *
 *
 * Too many blank lines above me.
 */
class Test
{
```

##### Good

```
<?php
$code = true;
$anotherLine = 'here';
```
```
<?php
$code = true;

$anotherLine = 'here';
```
```
<?php
/**
 * This is a docblock
 *
 * Only the maximum 1 blank line above me.
 */
class Test
{
```

### File Header

File header rules exist as expected within the PSR-12 standard with one exception.

There **can not** be a blank line between the opening `<?php` tag and the file level docblock.

#### Example

```
<?php
/**
 * @file
 * No blank lines between me and the <?php
 */
```
