<?php

namespace Recursed;

use PhpParser\Node;
use SplFileInfo;

/**
 * Value object representing a recursive call.
 */
class RecursiveCall
{
    /**
     * @var Node The node in the AST representing the code where the recursive function is declared
     */
    private $declarationNode;

    /**
     * @var Node The node in the AST representing the code of the recursive call
     */
    private $usageNode;

    /**
     * @var SplFileInfo The file containing the recursive call
     */
    private $file;

    /**
     * @param Node        $usageNode
     * @param Node        $declarationNode
     * @param SplFileInfo $file
     */
    public function __construct(Node $usageNode, Node $declarationNode, SplFileInfo $file)
    {
        $this->usageNode = $usageNode;
        $this->declarationNode = $declarationNode;
        $this->file = $file;
    }

    /**
     * @return Node
     */
    public function getDeclarationNode()
    {
        return $this->declarationNode;
    }

    /**
     * @return Node
     */
    public function getUsageNode()
    {
        return $this->usageNode;
    }

    /**
     * @return SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }
}
