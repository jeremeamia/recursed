<?php

namespace Recursed;

use PhpParser\PrettyPrinter\Standard as StandardNodePrinter;
use PhpParser\PrettyPrinterAbstract as NodePrinter;

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
     * @param RecursiveCall $call
     */
    public function printSingleRecursiveCall(RecursiveCall $call)
    {
        echo "LOCATED IN FILE: " . $call->getFile()->getRealPath() . "\n";
        echo "DECLARED ON LINE #" . $call->getDeclarationNode()->getLine() . "\n";
        echo "CALLED ON LINE #" . $call->getUsageNode()->getLine() . "\n";
        echo "USAGE CODE:\n" . $this->nodePrinter->prettyPrint(array($call->getUsageNode())) . "\n\n";
    }
}
