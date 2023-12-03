<?php

declare(strict_types=1);

/*
 * This file is part of Analyzer.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Analyzer;

use Closure;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types\Context;
use phpDocumentor\Reflection\Types\ContextFactory;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeVisitorAbstract;

/**
 * This is the doc visitor class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
final class DocVisitor extends NodeVisitorAbstract
{
    /**
     * The context factory.
     *
     * @var \Closure(string): Context
     */
    private Closure $contextFactory;

    /**
     * The phpdoc factory.
     *
     * @var \Closure(string, Context): DocBlock
     */
    private Closure $phpdocFactory;

    /**
     * The current context.
     *
     * @var \phpDocumentor\Reflection\Types\Context|null
     */
    private ?Context $context = null;

    /**
     * The recorded phpdoc.
     *
     * @var \phpDocumentor\Reflection\DocBlock[]|null
     */
    private ?array $doc = null;

    /**
     * Create a new doc visitor aware of file contents.
     *
     * @param string $contents
     *
     * @return \GrahamCampbell\Analyzer\DocVisitor
     */
    public static function create(string $contents): self
    {
        $contextInst = new ContextFactory();

        $context = function (string $namespace) use ($contents, $contextInst): Context {
            return $contextInst->createForNamespace($namespace, $contents);
        };

        $phpdocInst = DocBlockFactory::createInstance();

        $phpdoc = function (string $doc, Context $context) use ($phpdocInst): DocBlock {
            return $phpdocInst->create($doc, $context);
        };

        return new self($context, $phpdoc);
    }

    /**
     * Create a new doc visitor instance.
     *
     * @param \Closure(string): Context           $context
     * @param \Closure(string, Context): DocBlock $phpdoc
     *
     * @return void
     */
    public function __construct(Closure $context, Closure $phpdoc)
    {
        $this->contextFactory = $context;
        $this->phpdocFactory = $phpdoc;
    }

    /**
     * Reset the recorded imports.
     *
     * @param \PhpParser\Node[] $nodes
     *
     * @return void
     */
    public function beforeTraverse(array $nodes): void
    {
        $this->resetContext();
        $this->doc = [];
    }

    /**
     * Enter the node and record the phpdoc.
     *
     * @param \PhpParser\Node $node
     *
     * @return \PhpParser\Node
     */
    public function enterNode(Node $node): Node
    {
        if ($node instanceof Namespace_) {
            $this->resetContext($node->name);
        }

        $this->recordDoc($node->getAttribute('comments', []));

        return $node;
    }

    /**
     * Reset the visitor context.
     *
     * @param \PhpParser\Node\Name|null $namespace
     *
     * @return void
     */
    protected function resetContext(Name $namespace = null): void
    {
        $callable = $this->contextFactory;

        $this->context = $callable($namespace ? $namespace->toString() : '');
    }

    /**
     * Reset the visitor context.
     *
     * @param \PhpParser\Comment[] $comments
     *
     * @return void
     */
    protected function recordDoc(array $comments): void
    {
        $callable = $this->phpdocFactory;

        foreach ($comments as $comment) {
            if ($comment instanceof Doc) {
                $this->doc[] = $callable($comment->getText(), $this->context);
            }
        }
    }

    /**
     * Get the recorded phpdoc.
     *
     * Returns null if not traversed yet.
     *
     * @return \phpDocumentor\Reflection\DocBlock[]|null
     */
    public function getDoc(): ?array
    {
        return $this->doc;
    }
}
