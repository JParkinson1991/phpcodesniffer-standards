# [JPSR-12](../JPSR12.md): Control Signatures

## List

- [Multi Keyword Controls](#multi-keyword-controls)

## Rules

### Multi Keyword Controls

The adjoining keyword in a multi keyword control **must** exist on the line after the closing brace of the previous keyword.

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
