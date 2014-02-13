# Recursed

Using recursion to look for recursion in code.

By Jeremy Lindblom

## Why?

I wanted to look for examples of recursive functions in various codebases.

## Installation

Install the `"jeremeamia/recursed"` package via Composer.

## Usage

```php
<?php

require 'vendor/autoload.php';

use Recursed\RecursiveCallFinder;
use Recursed\RecursiveCallPrinter;

$path = 'PATH_TO_CODE'; // e.g. ~/code/Guzzle/src

$finder = new RecursiveCallFinder;
$printer = new RecursiveCallPrinter;

$printer->printRecursiveCalls($finder->findRecursionInDirectory($path));
```

### Output

```
LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Common/Collection.php
DECLARED ON LINE #354
CALLED ON LINE #374
USAGE CODE:
$this->getPath($path, $separator, $value);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Common/Exception/ExceptionCollection.php
DECLARED ON LINE #88
CALLED ON LINE #99
USAGE CODE:
$this->getExceptionMessage($ee, $depth + 1);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Http/Message/EntityEnclosingRequest.php
DECLARED ON LINE #183
CALLED ON LINE #192
USAGE CODE:
$this->addPostFile($field, $file, $contentType);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Builder/ServiceBuilder.php
DECLARED ON LINE #104
CALLED ON LINE #116
USAGE CODE:
$this->get($actualName, $throwAway);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Builder/ServiceBuilder.php
DECLARED ON LINE #104
CALLED ON LINE #131
USAGE CODE:
$this->get(trim($v, '{} '));

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Command/LocationVisitor/Response/JsonVisitor.php
DECLARED ON LINE #49
CALLED ON LINE #59
USAGE CODE:
$this->recursiveProcess($param->getItems(), $item);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Command/LocationVisitor/Response/JsonVisitor.php
DECLARED ON LINE #49
CALLED ON LINE #70
USAGE CODE:
$this->recursiveProcess($property, $value[$key]);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Command/LocationVisitor/Response/JsonVisitor.php
DECLARED ON LINE #49
CALLED ON LINE #85
USAGE CODE:
$this->recursiveProcess($additional, $v);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Command/LocationVisitor/Response/XmlVisitor.php
DECLARED ON LINE #44
CALLED ON LINE #51
USAGE CODE:
// Cast to an array if the value was a string, but should be an array
$this->recursiveProcess($param->getItems(), $value);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Description/SchemaValidator.php
DECLARED ON LINE #76
CALLED ON LINE #134
USAGE CODE:
$this->recursiveProcess($property, $value[$name], $path, $depth + 1);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Description/SchemaValidator.php
DECLARED ON LINE #76
CALLED ON LINE #137
USAGE CODE:
$this->recursiveProcess($property, $current, $path, $depth + 1);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Description/SchemaValidator.php
DECLARED ON LINE #76
CALLED ON LINE #156
USAGE CODE:
$this->recursiveProcess($additional, $value[$key], "{$path}[{$key}]", $depth);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Description/SchemaValidator.php
DECLARED ON LINE #76
CALLED ON LINE #178
USAGE CODE:
// Validate each item in an array against the items attribute of the schema
$this->recursiveProcess($param->getItems(), $item, $path . "[{$i}]", $depth + 1);
```
