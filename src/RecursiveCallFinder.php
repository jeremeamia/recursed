<?php

namespace Recursed;

use PhpParser\Parser;
use PhpParser\Lexer\Emulative;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class RecursiveCallFinder
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @param Parser $parser
     */
    public function __construct(Parser $parser = null)
    {
        $this->parser = $parser ? : new Parser(new Emulative);
    }

    /**
     * @param SplFileInfo|string $file
     *
     * @return RecursiveCallIterator
     */
    public function findRecursionInFile($file)
    {
        $file = ($file instanceof SplFileInfo) ? $file : new SplFileInfo($file);
        $ast = $this->parser->parse(file_get_contents($file->getRealPath()));

        $traverser = new NodeTraverser;
        $visitor = new RecursiveCallFinderNodeVisitor($file);
        $traverser->addVisitor($visitor);
        $traverser->traverse($ast);

        return new RecursiveCallIterator($visitor->getRecursiveCalls());
    }

    /**
     * @param string $directory
     *
     * @return RecursiveCallIterator
     */
    public function findRecursionInDirectory($directory)
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        $recursiveCalls = new RecursiveCallIterator();
        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $recursiveCalls->append($this->findRecursionInFile($file));
            }
        }

        return $recursiveCalls;
    }
}

