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

namespace GrahamCampbell\Tests\Analyzer;

use GrahamCampbell\Analyzer\ReferenceAnalyzer;
use PHPUnit\Framework\TestCase;

class ReferenceAnalyzerTest extends TestCase
{
    public function testCanGenerateRefs()
    {
        $refs = (new ReferenceAnalyzer())->analyze(__FILE__);

        $this->assertSame([
            'GrahamCampbell\Analyzer\ReferenceAnalyzer',
            'PHPUnit\Framework\TestCase',
        ], $refs);
    }

    public function testCanGenerateMoreRefs()
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/../src/ReferenceAnalyzer.php');

        $this->assertSame([
            'PhpParser\NodeTraverser',
            'PhpParser\NodeVisitor\NameResolver',
            'PhpParser\Parser',
            'PhpParser\ParserFactory',
            'GrahamCampbell\Analyzer\ImportVisitor',
            'GrahamCampbell\Analyzer\NameVisitor',
            'GrahamCampbell\Analyzer\DocVisitor',
            'GrahamCampbell\Analyzer\DocProcessor',
        ], $refs);
    }

    public function testCanGenerateUsingFuncStub()
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/stubs/func.php');

        $this->assertSame([], $refs);
    }

    public function testCanGenerateUsingBoolStub()
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/stubs/bool.php');

        $this->assertSame([], $refs);
    }

    public function testCanGenerateUsingEgStub()
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/stubs/eg.php');

        $this->assertSame(['Foo\\Baz', 'Foo\\Bar'], $refs);
    }
}
