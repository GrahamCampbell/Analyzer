<?php

declare(strict_types=1);

/*
 * This file is part of Analyzer.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Analyzer;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PhpParser\ParserFactory;

/**
 * This is the reference analyzer class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ReferenceAnalyzer
{
    /**
     * The parser instance.
     *
     * @var \PhpParser\Parser
     */
    protected $parser;

    /**
     * Create a new reference analyzer instance.
     *
     * @param \PhpParser\Parser|null $parser
     *
     * @return void
     */
    public function __construct(Parser $parser = null)
    {
        $this->parser = $parser ?: (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
    }

    /**
     * Get the fullyqualified imports and typehints.
     *
     * @param string $path
     *
     * @return string[]
     */
    public function analyze(string $path)
    {
        $contents = (string) file_get_contents($path);

        $traverser = new NodeTraverser();

        $traverser->addVisitor(new NameResolver());
        $traverser->addVisitor($imports = new ImportVisitor());
        $traverser->addVisitor($names = new NameVisitor());
        $traverser->addVisitor($docs = DocVisitor::create($contents));

        $traverser->traverse($this->parser->parse($contents));

        return array_values(array_unique(array_merge(
            $imports->getImports(),
            $names->getNames(),
            DocProcessor::process($docs->getDoc())
        )));
    }
}
