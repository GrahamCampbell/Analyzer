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

namespace GrahamCampbell\Tests\Analyzer;

use GrahamCampbell\Analyzer\ReferenceAnalyzer;
use PHPUnit\Framework\TestCase;

class ReferenceAnalyzerTest extends TestCase
{
    public function testCanGenerateRefs(): void
    {
        $refs = (new ReferenceAnalyzer())->analyze(__FILE__);

        self::assertSame([
            'GrahamCampbell\Analyzer\ReferenceAnalyzer',
            'PHPUnit\Framework\TestCase',
        ], $refs);
    }

    public function testCanGenerateMoreRefs(): void
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/../src/ReferenceAnalyzer.php');

        self::assertSame([
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

    public function testCanGenerateUsingFuncStub(): void
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/stubs/func.php');

        self::assertSame([], $refs);
    }

    public function testCanGenerateUsingBoolStub(): void
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/stubs/bool.php');

        self::assertSame([], $refs);
    }

    public function testCanGenerateUsingEgStub(): void
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/stubs/eg.php');

        self::assertSame(['Foo\\Baz', 'Foo\\Bar'], $refs);
    }
}
