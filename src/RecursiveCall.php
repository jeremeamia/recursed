<?php

namespace Recursed;

use PhpParser\Node;
use SplFileInfo;

class RecursiveCall
{
    /**
     * @var Node
     */
    private $declarationNode;

    /**
     * @var Node
     */
    private $usageNode;

    /**
     * @var SplFileInfo
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
