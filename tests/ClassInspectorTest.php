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

use GrahamCampbell\Analyzer\AnalysisTrait;
use GrahamCampbell\Analyzer\ClassInspector;
use InvalidArgumentException;
use PhpParser\NodeTraverserInterface;
use PHPUnit\Framework\TestCase;

class ClassInspectorTest extends TestCase
{
    public function testCanInspectClasses(): void
    {
        $inspector = ClassInspector::inspect(static::class);

        self::assertInstanceOf(ClassInspector::class, $inspector);

        self::assertTrue($inspector->isClass());
        self::assertFalse($inspector->isInterface());
        self::assertFalse($inspector->isTrait());
        self::assertTrue($inspector->exists());

        self::assertSame([
            'GrahamCampbell\Analyzer\AnalysisTrait',
            'GrahamCampbell\Analyzer\ClassInspector',
            'InvalidArgumentException',
            'PhpParser\NodeTraverserInterface',
            'PHPUnit\Framework\TestCase',
        ], $inspector->references());
    }

    public function testCanInspectInterfaces(): void
    {
        $inspector = ClassInspector::inspect(NodeTraverserInterface::class);

        self::assertInstanceOf(ClassInspector::class, $inspector);

        self::assertFalse($inspector->isClass());
        self::assertTrue($inspector->isInterface());
        self::assertFalse($inspector->isTrait());
        self::assertTrue($inspector->exists());

        self::assertSame(['PhpParser\NodeVisitor', 'PhpParser\Node'], $inspector->references());
    }

    public function testCanInspectTraits(): void
    {
        $inspector = ClassInspector::inspect(AnalysisTrait::class);

        self::assertInstanceOf(ClassInspector::class, $inspector);

        self::assertFalse($inspector->isClass());
        self::assertFalse($inspector->isInterface());
        self::assertTrue($inspector->isTrait());
        self::assertTrue($inspector->exists());

        self::assertSame([
            'AppendIterator',
            'CallbackFilterIterator',
            'RecursiveDirectoryIterator',
            'RecursiveIteratorIterator',
            'SplFileInfo',
            'GrahamCampbell\Analyzer\ReferenceAnalyzer',
            'GrahamCampbell\Analyzer\ClassInspector',
        ], $inspector->references());
    }

    public function testCanInspectNothing(): void
    {
        $inspector = ClassInspector::inspect('foobarbaz');

        self::assertInstanceOf(ClassInspector::class, $inspector);

        self::assertFalse($inspector->isClass());
        self::assertFalse($inspector->isInterface());
        self::assertFalse($inspector->isTrait());
        self::assertFalse($inspector->exists());

        self::assertSame([], $inspector->references());
    }

    public function testCanNotInspectEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The class name must be non-empty.');
        $inspector = ClassInspector::inspect('');
    }
}
