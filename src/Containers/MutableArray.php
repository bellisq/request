<?php

namespace Bellisq\Request\Containers;

use ArrayAccess;
use Bellisq\Request\Exceptions\IllegalOffsetTypeException;
use Bellisq\Request\Exceptions\UndefinedOffsetException;


/**
 * [Class] Mutable Array
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 */
class MutableArray
    implements ArrayAccess
{
    /**
     * MutableArray constructor.
     *
     * @param array $source
     */
    public function __construct(array $source = [])
    {
        $this->assign($source);
    }

    /**
     * @return array
     */
    public function getArrayCopy(): array
    {
        return $this->data;
    }

    /**
     * @param array $newArray
     */
    public function assign(array $newArray): void
    {
        $this->data = $newArray;
    }

    /**
     * Implementation of ArrayAccess::offsetGet
     *
     * @param mixed $offset
     * @return mixed
     *
     * @throws UndefinedOffsetException
     * @throws IllegalOffsetTypeException
     */
    public function &offsetGet($offset)
    {
        self::validateOffset($offset);
        $this->existsOrFail($offset);
        return $this->data[$offset];
    }

    /**
     * Implementation of ArrayAccess::offsetSet
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @throws IllegalOffsetTypeException
     */
    public function offsetSet($offset, $value): void
    {
        self::validateOffset($offset);
        $this->data[$offset] = $value;
    }

    /**
     * Implementation of ArrayAccess::offsetExists
     *
     * @param mixed $offset
     * @return bool
     *
     * @throws IllegalOffsetTypeException
     */
    public function offsetExists($offset): bool
    {
        self::validateOffset($offset);
        return array_key_exists($offset, $this->data);
    }

    /**
     * Implementation of ArrayAccess::offsetUnset
     *
     * @param mixed $offset
     *
     * @throws UndefinedOffsetException
     * @throws IllegalOffsetTypeException
     */
    public function offsetUnset($offset): void
    {
        self::validateOffset($offset);
        $this->existsOrFail($offset);
        unset($this->data[$offset]);
    }

    /** @var array */
    protected $data;

    /**
     * @param $offset
     *
     * @throws IllegalOffsetTypeException
     */
    protected static function validateOffset($offset): void
    {
        if (!is_string($offset) && !is_numeric($offset)) {
            throw new IllegalOffsetTypeException;
        }
    }

    /**
     * @param $offset
     *
     * @throws UndefinedOffsetException
     */
    protected function existsOrFail($offset): void
    {
        if (!$this->offsetExists($offset)) {
            throw new UndefinedOffsetException;
        }
    }

    /**
     * Magic method
     */
    public function __clone()
    {
        $this->data = self::cloneArray($this->data);
    }

    /**
     * @param array $input
     * @return array
     */
    private static function cloneArray(array $input): array
    {
        $output = [];
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $output[$key] = self::cloneArray($value);
            } else if (is_object($value)) {
                $output[$key] = clone $value;
            } else {
                $output[$key] = $value;
            }
        }
        return $output;
    }
}