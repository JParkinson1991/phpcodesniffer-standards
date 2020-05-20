# [JPSR-12](../JPSR12.md): Control Signatures

## List

- [Multi Keyword Controls](#multi-keyword-controls)

## Rules

### Multi Keyword Controls

The adjoining keyword in a multi keyword control **must** exist on the line
after the closing brace of the previous keyword.

Only 1 newline may exist between the adjoining keyword, and the previous
keyword's closing brace. Any more than 1 newline will be treat as an error.

Comments (or anything else) **must not** be placed between keywords in multi key
word structures. These will be treated as errors and removed on automatic
cleanup/beautify.

#### Example

```
<?php

if (true) {
}
else if (false) {
}
else {
}

try {
}
catch (Exception $e) {
}
```
