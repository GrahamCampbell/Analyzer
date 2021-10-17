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
    abstract protected function getPaths();

    /**
     * Determine if the given file should be analyzed.
     *
     * @param \SplFileInfo $file
     *
     * @return bool
     */
    protected function shouldAnalyzeFile(SplFileInfo $file)
    {
        return true;
    }

    /**
     * Test all class references exist.
     *
     * @dataProvider provideFilesToCheck
     */
    public function testReferences(string $file)
    {
        $this->assertTrue(file_exists($file), "Expected {$file} to exist.");

        $ignored = method_exists($this, 'getIgnored') ? $this->getIgnored() : [];

        foreach ((new ReferenceAnalyzer())->analyze($file) as $class) {
            if (in_array($class, $ignored, true)) {
                continue;
            }

            $this->assertTrue(ClassInspector::inspect($class)->exists(), "Expected {$class} to exist.");
        }
    }

    /**
     * Get the files to check.
     *
     * @return string[][]
     */
    public function provideFilesToCheck()
    {
        $iterator = new AppendIterator();

        foreach ($this->getPaths() as $path) {
            $iterator->append(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)));
        }

        $files = new CallbackFilterIterator($iterator, function ($file) {
            return $file->getFilename()[0] !== '.' && !$file->isDir() && $this->shouldAnalyzeFile($file);
        });

        return array_map(function ($file) {
            return [(string) $file];
        }, iterator_to_array($files));
    }
}
