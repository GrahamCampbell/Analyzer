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
    public function testCanInspectClasses()
    {
        $inspector = ClassInspector::inspect(static::class);

        $this->assertInstanceOf(ClassInspector::class, $inspector);

        $this->assertTrue($inspector->isClass());
        $this->assertFalse($inspector->isInterface());
        $this->assertFalse($inspector->isTrait());
        $this->assertTrue($inspector->exists());

        $this->assertSame([
            'GrahamCampbell\Analyzer\AnalysisTrait',
            'GrahamCampbell\Analyzer\ClassInspector',
            'InvalidArgumentException',
            'PhpParser\NodeTraverserInterface',
            'PHPUnit\Framework\TestCase',
        ], $inspector->references());
    }

    public function testCanInspectInterfaces()
    {
        $inspector = ClassInspector::inspect(NodeTraverserInterface::class);

        $this->assertInstanceOf(ClassInspector::class, $inspector);

        $this->assertFalse($inspector->isClass());
        $this->assertTrue($inspector->isInterface());
        $this->assertFalse($inspector->isTrait());
        $this->assertTrue($inspector->exists());

        $this->assertSame(['PhpParser\NodeVisitor', 'PhpParser\Node'], $inspector->references());
    }

    public function testCanInspectTraits()
    {
        $inspector = ClassInspector::inspect(AnalysisTrait::class);

        $this->assertInstanceOf(ClassInspector::class, $inspector);

        $this->assertFalse($inspector->isClass());
        $this->assertFalse($inspector->isInterface());
        $this->assertTrue($inspector->isTrait());
        $this->assertTrue($inspector->exists());

        $this->assertSame([
            'AppendIterator',
            'CallbackFilterIterator',
            'RecursiveDirectoryIterator',
            'RecursiveIteratorIterator',
            'SplFileInfo',
            'GrahamCampbell\Analyzer\ReferenceAnalyzer',
            'GrahamCampbell\Analyzer\ClassInspector',
        ], $inspector->references());
    }

    public function testCanInspectNothing()
    {
        $inspector = ClassInspector::inspect('foobarbaz');

        $this->assertInstanceOf(ClassInspector::class, $inspector);

        $this->assertFalse($inspector->isClass());
        $this->assertFalse($inspector->isInterface());
        $this->assertFalse($inspector->isTrait());
        $this->assertFalse($inspector->exists());

        $this->assertSame([], $inspector->references());
    }

    public function testCanNotInspectEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The class name must be non-empty.');
        $inspector = ClassInspector::inspect('');
    }
}
