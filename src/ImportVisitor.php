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

use PhpParser\Node;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\NodeVisitorAbstract;

/**
 * This is the import visitor class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ImportVisitor extends NodeVisitorAbstract
{
    /**
     * The recorded imports.
     *
     * @var string[]
     */
    protected $imports = [];

    /**
     * Enter the node and record the import.
     *
     * @param \PhpParser\Node $node
     *
     * @return \PhpParser\Node
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof UseUse) {
            $this->imports[] = $node->name->toString();
        }

        return $node;
    }

    /**
     * Get the recorded imports.
     *
     * @return string[]
     */
    public function getImports()
    {
        return $this->imports;
    }
}
