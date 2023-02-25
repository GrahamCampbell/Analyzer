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

use AppendIterator;
use CallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * This is the analysis trait.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
trait AnalysisTrait
{
    /**
     * Get the code paths to analyze.
     *
     * @return string[]
     */
    abstract protected static function getPaths(): array;

    /**
     * Determine if the given file should be analyzed.
     *
     * @param \SplFileInfo $file
     *
     * @return bool
     */
    protected static function shouldAnalyzeFile(SplFileInfo $file): bool
    {
        return true;
    }

    /**
     * Test all class references exist.
     *
     * @dataProvider provideFilesToCheck
     */
    public function testReferences(string $file): void
    {
        static::assertTrue(file_exists($file), "Expected {$file} to exist.");

        $ignored = method_exists($this, 'getIgnored') ? static::getIgnored() : [];

        foreach ((new ReferenceAnalyzer())->analyze($file) as $class) {
            if (in_array($class, $ignored, true)) {
                continue;
            }

            static::assertTrue(ClassInspector::inspect($class)->exists(), "Expected {$class} to exist.");
        }
    }

    /**
     * Get the files to check.
     *
     * @return string[][]
     */
    public static function provideFilesToCheck(): array
    {
        $iterator = new AppendIterator();

        foreach (static::getPaths() as $path) {
            $iterator->append(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)));
        }

        $files = new CallbackFilterIterator($iterator, function ($file) {
            return $file->getFilename()[0] !== '.' && !$file->isDir() && static::shouldAnalyzeFile($file);
        });

        return array_map(function ($file) {
            return [(string) $file];
        }, iterator_to_array($files));
    }
}
