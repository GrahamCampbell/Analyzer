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
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\NodeVisitorAbstract;

/**
 * This is the name visitor class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class NameVisitor extends NodeVisitorAbstract
{
    /**
     * The recorded names.
     *
     * @var string[]|null
     */
    protected $names;

    /**
     * Reset the recorded names.
     *
     * @param \PhpParser\Node[] $nodes
     *
     * @return void
     */
    public function beforeTraverse(array $nodes)
    {
        $this->names = [];
    }

    /**
     * Enter the node and record the name.
     *
     * @param \PhpParser\Node $node
     *
     * @return \PhpParser\Node
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof ConstFetch || $node instanceof FuncCall) {
            $node->name = null;
        }

        if ($node instanceof FullyQualified) {
            $this->names[] = $node->toString();
        }

        return $node;
    }

    /**
     * Get the recorded names.
     *
     * Returns null if not traversed yet.
     *
     * @return string[]|null
     */
    public function getNames()
    {
        return $this->names;
    }
}
