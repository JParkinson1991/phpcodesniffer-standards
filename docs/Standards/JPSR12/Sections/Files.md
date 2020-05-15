# [JPSR-12](../JSPR12.md): Files

## List

- [File Header](#file-header)

## Rules

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

