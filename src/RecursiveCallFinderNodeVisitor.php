<?php

namespace Recursed;

use PhpParser\Node\Expr\Assign as AssignNode;
use PhpParser\Node\Expr\Closure as ClosureNode;
use PhpParser\Node\Expr\FuncCall as FuncCallerNode;
use PhpParser\Node\Expr\MethodCall as MethodCallerNode;
use PhpParser\Node\Expr\StaticCall as StaticCallerNode;
use PhpParser\Node\Stmt\ClassMethod as MethodNode;
use PhpParser\Node\Stmt\Function_ as FuncNode;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use SplFileInfo;
use SplStack;

/**
 * A visitor object that identifies recursive calls as the AST of the PHP code is traversed.
 *
 * @package Recursed
 */
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
        } elseif ($node instanceof FuncCallerNode
            || $node instanceof MethodCallerNode
            || $node instanceof StaticCallerNode
        ) {
            $this->handleCallerNode($node);
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
     * @param Node $callerNode
     */
    private function handleCallerNode(Node $callerNode)
    {
        // If the caller node does not have a name or there is no context node, it's definitely not recursive
        // NOTE: Caller nodes may not have a name if they represent closures that were not assigned to a variable
        // NOTE: There will be no context node if the function call occurs outside of a method/function declaration
        if (!isset($callerNode->name) || $this->nodeStack->isEmpty()) {
            return;
        }

        // Determine the context node by looking at the stack
        $contextNode = $this->nodeStack->top();

        // Determine the caller node's name
        if (isset($callerNode->name->parts)) {
            // NOTE: This makes some blind assumptions on how `parts` is structured, but it works for now
            $callerNodeName = $callerNode->name->parts[0];
        } else {
            // Handle the name whether it is a string or a Name node
            $callerNodeName = is_string($callerNode->name) ? $callerNode->name : $callerNode->name->name;
        }

        // Handle each type of caller node
        $isRecursive = false;
        if ($callerNode instanceof FuncCallerNode) {
            // Ensure that the names of the functions being called and defined are the same
            $isRecursive = $contextNode instanceof FuncNode && $contextNode->name === $callerNodeName;
        } elseif ($callerNode instanceof MethodCallerNode) {
            // Ensure that the names of the methods being called and defined are the same
            $isRecursive = $contextNode instanceof MethodNode && $contextNode->name === $callerNodeName;
            // Only accept method calls if the method is being called on `$this`
            // NOTE: If the method is being called on a different or dynamic variable name, then this won't work
            $isRecursive = $isRecursive && $callerNode->var->name === 'this';
        } elseif ($callerNode instanceof StaticCallerNode) {
            // Ensure that the names of the methods being called and defined are the same
            $isRecursive = $contextNode instanceof MethodNode && $contextNode->name === $callerNodeName;
            // Only accept method calls if the method is being called on `self` or `static`
            $className = $callerNode->class->parts[0];
            // NOTE: If the method is being called on a direct class name reference, then this won't work
            $isRecursive = $isRecursive && ($className === 'self' || $className === 'static');
        }

        // Save the call node as a recursive call if the criteria was met for its type
        if ($isRecursive) {
            $this->recursiveCalls[] = new RecursiveCall($callerNode, $contextNode, $this->file);
        }
    }
}
