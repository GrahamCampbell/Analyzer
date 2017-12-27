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

use phpDocumentor\Reflection\DocBlock\Tags\BaseTag;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;

/**
 * This is the doc processor class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DocProcessor
{
    /**
     * Process an array of phpdoc.
     *
     * Returns FQCN strings for each reference.
     *
     * @param \phpDocumentor\Reflection\DocBlock[] $docs
     *
     * @return string[]
     */
    public static function process(array $docs)
    {
        return self::flatmap(function ($doc) {
            return self::flatmap(function ($tag) {
                return self::flatmap(function ($type) {
                    return self::processType($type);
                }, self::processTag($tag));
            }, $doc->getTags());
        }, $docs);
    }

    /**
     * Apply the function and flatten the result.
     *
     * @param callable $fn
     * @param array    $array
     *
     * @return array
     */
    private static function flatmap(callable $fn, array $array)
    {
        if (empty($array)) {
            return [];
        }

        return array_merge(...array_map($fn, $array));
    }

    /**
     * Process a tag into types.
     *
     * @param \phpDocumentor\Reflection\DocBlock\Tags\BaseTag $tag
     *
     * @return \phpDocumentor\Reflection\Type[]
     */
    private static function processTag(BaseTag $tag)
    {
        $types = [];

        if (method_exists($tag, 'getType') && is_callable([$tag, 'getType'])) {
            if (($type = $tag->getType()) !== null) {
                $types[] = $type;
            }
        }

        if (method_exists($tag, 'getArguments') && is_callable([$tag, 'getArguments'])) {
            foreach ($tag->getArguments() as $param) {
                if (($type = $param['type'] ?? null) !== null) {
                    $types[] = $type;
                }
            }
        }

        return $types;
    }

    /**
     * Process a type into FQCN strings.
     *
     * @param \phpDocumentor\Reflection\Type $type
     *
     * @return string[]
     */
    private static function processType(Type $type)
    {
        // TODO type-resolver v1 compat
        // if ($type instanceof AbstractList) {
        if ($type instanceof Array_) {
            return self::flatmap(function ($t) {
                return self::processType($t);
            }, [$type->getKeyType(), $type->getValueType()]);
        }

        if ($type instanceof Compound) {
            return self::flatmap(function ($t) {
                return self::processType($t);
            }, iterator_to_array($type));
        }

        if ($type instanceof Nullable) {
            return self::processType($type->getActualType());
        }

        if ($type instanceof Object_) {
            if (($fq = $type->getFqsen()) !== null) {
                return [ltrim((string) $fq, '\\')];
            }
        }

        return [];
    }
}
