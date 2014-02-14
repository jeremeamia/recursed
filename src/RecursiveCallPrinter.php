<?php

namespace Recursed;

use PhpParser\PrettyPrinter\Standard as StandardNodePrinter;
use PhpParser\PrettyPrinterAbstract as NodePrinter;

/**
 * Used to print the results of the RecursiveCallFinder.
 */
class RecursiveCallPrinter
{
    /**
     * @var NodePrinter
     */
    private $nodePrinter;

    /**
     * @param NodePrinter $nodePrinter
     */
    public function __construct(NodePrinter $nodePrinter = null)
    {
        $this->nodePrinter = $nodePrinter ? : new StandardNodePrinter;
    }

    /**
     * @param RecursiveCallIterator $recursiveCalls
     */
    public function printRecursiveCalls(RecursiveCallIterator $recursiveCalls)
    {
        /** @var RecursiveCall $recursiveCall */
        foreach ($recursiveCalls as $recursiveCall) {
            $this->printSingleRecursiveCall($recursiveCall);
        }
    }

    /**
     * Prints information about a single recursive call. Override to change format.
     *
     * @param RecursiveCall $call
     */
    public function printSingleRecursiveCall(RecursiveCall $call)
    {
        // Get the code for the recursive call, but strip out comment lines
        $code = $this->nodePrinter->prettyPrint(array($call->getUsageNode()));
        $code = implode("\n", array_filter(explode("\n", $code), function ($line) {
            return !preg_match('#^[\t ]*//#', $line);
        }));

        // Create the output that indicates where the recursive call was found
        echo "LOCATED IN FILE: " . $call->getFile()->getRealPath() . "\n";
        echo "DECLARED ON LINE #" . $call->getDeclarationNode()->getLine() . " ";
        echo "AND CALLED ON LINE #" . $call->getUsageNode()->getLine() . "\n";
        echo "CALLING CODE: {$code}\n\n";
    }
}
