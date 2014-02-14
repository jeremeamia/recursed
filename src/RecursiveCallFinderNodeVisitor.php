<?php

namespace Recursed;

use PhpParser\Node\Expr\Assign as AssignNode;
use PhpParser\Node\Expr\Closure as ClosureNode;
use PhpParser\Node\Expr\FuncCall as FuncCallNode;
use PhpParser\Node\Expr\MethodCall as MethodCallNode;
use PhpParser\Node\Expr\StaticCall as StaticCallNode;
use PhpParser\Node\Stmt\ClassMethod as MethodNode;
use PhpParser\Node\Stmt\Function_ as FuncNode;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use SplFileInfo;
use SplStack;

class RecursiveCallFinderNodeVisitor extends NodeVisitorAbstract
{
    /**
     * @var SplStack
     */
    private $nodeStack;

    /**
     * @var SplFileInfo
     */
    private $file;

    /**
     * @var RecursiveCall[]
     */
    private $recursiveCalls;

    /**
     * @param \SplFileInfo $file
     * @param SplStack     $stack
     */
    public function __construct(SplFileInfo $file, SplStack $stack = null)
    {
        $this->file = $file;
        $this->nodeStack = $stack ? : new SplStack;
        $this->recursiveCalls = array();
    }

    public function enterNode(Node $node)
    {
        // Track function declarations
        if ($node instanceof FuncNode) {
            $this->nodeStack->push($node);
        // Track method declarations
        } elseif ($node instanceof MethodNode) {
            $this->nodeStack->push($node);
        // Track closure declarations when assigned to a variable
        } elseif ($node instanceof AssignNode && $node->expr instanceof ClosureNode) {
            $node->name = $node->var->name;
            $this->nodeStack->push($node);
        // Determine if a function or method call is recursive
        } elseif ($node instanceof FuncCallNode) {
            $name = $this->getNodeName($node);
            if ($name && !$this->nodeStack->isEmpty()) {
                $scopeNode = $this->nodeStack->top();
                // If the function being called is the same as the function being defined, then it's recursive
                if ($scopeNode instanceof FuncNode && $scopeNode->name === $name) {
                    $this->recursiveCalls[] = new RecursiveCall($node, $this->nodeStack->top(), $this->file);
                }
            }
        } elseif ($node instanceof MethodCallNode) {
            $name = $this->getNodeName($node);
            if ($name && !$this->nodeStack->isEmpty()) {
                $scopeNode = $this->nodeStack->top();
                // If the method being called is the same as the method being defined, then it's recursive
                // Note: Only accept method calls if the method is being called on $this
                if ($scopeNode instanceof MethodNode && $scopeNode->name === $name && $node->var->name === 'this') {
                    $this->recursiveCalls[] = new RecursiveCall($node, $this->nodeStack->top(), $this->file);
                }
            }
        } elseif ($node instanceof StaticCallNode) {
            $name = $this->getNodeName($node);
            if ($name && !$this->nodeStack->isEmpty()) {
                $scopeNode = $this->nodeStack->top();
                // If the method being called is the same as the method being defined, then it's recursive
                // Note: Only accept method calls if the method is being called on self or static
                $className = $node->class->parts[0];
                if ($scopeNode instanceof MethodNode && $scopeNode->name === $name && ($className === 'self' || $className === 'static')) {
                    $this->recursiveCalls[] = new RecursiveCall($node, $this->nodeStack->top(), $this->file);
                }
            }
        }
    }

    public function leaveNode(Node $node)
    {
        // Pop off the stack once the entire node's contents has been visited
        if (!$this->nodeStack->isEmpty() && $this->nodeStack->top() === $node) {
            $this->nodeStack->pop();
        }
    }

    /**
     * @return RecursiveCall[]
     */
    public function getRecursiveCalls()
    {
        return $this->recursiveCalls;
    }

    /**
     * @param Node $node
     *
     * @return null|string
     */
    private function getNodeName(Node $node)
    {
        if (isset($node->name)) {
            if (isset($node->name->parts)) {
                return $node->name->parts[0];
            } else {
                return is_string($node->name) ? $node->name : $node->name->name;
            }
        } else {
            return null;
        }
    }
}
