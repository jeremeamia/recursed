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
DECLARED ON LINE #354 AND CALLED ON LINE #374
CALLING CODE: $this->getPath($path, $separator, $value);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Common/Exception/ExceptionCollection.php
DECLARED ON LINE #88 AND CALLED ON LINE #99
CALLING CODE: $this->getExceptionMessage($ee, $depth + 1);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Http/Message/EntityEnclosingRequest.php
DECLARED ON LINE #183 AND CALLED ON LINE #192
CALLING CODE: $this->addPostFile($field, $file, $contentType);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Http/QueryAggregator/PhpAggregator.php
DECLARED ON LINE #12 AND CALLED ON LINE #19
CALLING CODE: self::aggregate($k, $v, $query);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Plugin/Oauth/OauthPlugin.php
DECLARED ON LINE #286 AND CALLED ON LINE #295
CALLING CODE: self::prepareParameters($value);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Builder/ServiceBuilder.php
DECLARED ON LINE #104 AND CALLED ON LINE #116
CALLING CODE: $this->get($actualName, $throwAway);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Builder/ServiceBuilder.php
DECLARED ON LINE #104 AND CALLED ON LINE #131
CALLING CODE: $this->get(trim($v, '{} '));

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Command/LocationVisitor/Response/JsonVisitor.php
DECLARED ON LINE #49 AND CALLED ON LINE #59
CALLING CODE: $this->recursiveProcess($param->getItems(), $item);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Command/LocationVisitor/Response/JsonVisitor.php
DECLARED ON LINE #49 AND CALLED ON LINE #70
CALLING CODE: $this->recursiveProcess($property, $value[$key]);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Command/LocationVisitor/Response/JsonVisitor.php
DECLARED ON LINE #49 AND CALLED ON LINE #85
CALLING CODE: $this->recursiveProcess($additional, $v);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Command/LocationVisitor/Response/XmlVisitor.php
DECLARED ON LINE #44 AND CALLED ON LINE #51
CALLING CODE: $this->recursiveProcess($param->getItems(), $value);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Description/SchemaValidator.php
DECLARED ON LINE #76 AND CALLED ON LINE #134
CALLING CODE: $this->recursiveProcess($property, $value[$name], $path, $depth + 1);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Description/SchemaValidator.php
DECLARED ON LINE #76 AND CALLED ON LINE #137
CALLING CODE: $this->recursiveProcess($property, $current, $path, $depth + 1);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Description/SchemaValidator.php
DECLARED ON LINE #76 AND CALLED ON LINE #156
CALLING CODE: $this->recursiveProcess($additional, $value[$key], "{$path}[{$key}]", $depth);

LOCATED IN FILE: ~/code/Guzzle/src/Guzzle/Service/Description/SchemaValidator.php
DECLARED ON LINE #76 AND CALLED ON LINE #178
CALLING CODE: $this->recursiveProcess($param->getItems(), $item, $path . "[{$i}]", $depth + 1);
```
