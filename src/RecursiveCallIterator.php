<?php

namespace Recursed;

use AppendIterator;
use ArrayIterator;
use InvalidArgumentException;
use Iterator;

/**
 * An iterator for RecursiveCall objects. Extends the AppendIterator to allow for easily joining sets of RecursiveCall
 * objects discovered in multiple files.
 */
class RecursiveCallIterator extends AppendIterator
{
    /**
     * @param RecursiveCall[] $recursiveCalls
     */
    public function __construct(array $recursiveCalls = array())
    {
        parent::__construct();
        if ($recursiveCalls) {
            $this->append(new ArrayIterator($recursiveCalls));
        }
    }

    /**
     * @param Iterator $iterator
     *
     * @throws InvalidArgumentException
     */
    public function append(Iterator $iterator)
    {
        foreach ($iterator as $item) {
            if (!($item instanceof RecursiveCall)) {
                throw new InvalidArgumentException();
            }
        }

        parent::append($iterator);
    }
}
