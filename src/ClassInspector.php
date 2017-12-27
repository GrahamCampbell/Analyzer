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

use InvalidArgumentException;
use ReflectionClass;

/**
 * This is the class inspector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ClassInspector
{
    /**
     * The class name.
     *
     * @var string
     */
    protected $class;

    /**
     * Inspect the given class.
     *
     * @param string $class
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public static function inspect(string $class)
    {
        if (!$class) {
            throw new InvalidArgumentException('The class name must be non-empty.');
        }

        return new static($class);
    }

    /**
     * Create a new class inspector instance.
     *
     * @param string $class
     *
     * @return void
     */
    protected function __construct(string $class)
    {
        $this->class = $class;
    }

    /**
     * Is the class a valid class?
     *
     * @return bool
     */
    public function isClass()
    {
        return class_exists($this->class);
    }

    /**
     * Is the class a valid interface?
     *
     * @return bool
     */
    public function isInterface()
    {
        return interface_exists($this->class);
    }

    /**
     * Is the class a valid trait?
     *
     * @return bool
     */
    public function isTrait()
    {
        return trait_exists($this->class);
    }

    /**
     * Does the class exist?
     *
     * @return bool
     */
    public function exists()
    {
        return $this->isClass() || $this->isInterface() || $this->isTrait();
    }

    /**
     * Get the native refector.
     *
     * @return \ReflectionClass|null
     */
    public function refector()
    {
        if (!$this->exists()) {
            return;
        }

        return new ReflectionClass($this->class);
    }

    /**
     * Get the fullyqualified imports and typehints.
     *
     * @return string[]
     */
    public function references()
    {
        if ($refector = $this->refector()) {
            return (new ReferenceAnalyzer())->analyze($refector->getFileName());
        } else {
            return [];
        }
    }
}
