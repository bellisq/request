<?php

namespace Bellisq\Request\Containers;

use Closure;
use DomainException;
use Strict\Property\Utility\StrictPropertyContainer;
use Strict\Property\Intermediate\PropertyRegister;
use TypeError;


/**
 * [Class] Data Container Accessor
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 */
abstract class DataContainerAccessor
    extends StrictPropertyContainer
{
    /** @var RequestDataContainer */
    protected $dataContainer;

    /**
     * DataContainerAccessor constructor.
     *
     * @param RequestDataContainer $rdc
     */
    public function __construct(RequestDataContainer $rdc)
    {
        parent::__construct();
        $this->dataContainer = $rdc;
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerStringProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                $this->generateStringGetter($containerKey),
                $this->generateStringSetter($containerKey)
            );
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerReadonlyStringProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                $this->generateStringGetter($containerKey),
                null
            );
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerNullableStringProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                $this->generateNullableStringGetter($containerKey),
                $this->generateNullableStringSetter($containerKey)
            );
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerReadonlyNullableStringProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                $this->generateNullableStringGetter($containerKey),
                null
            );
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerIntProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                $this->generateIntGetter($containerKey),
                $this->generateIntSetter($containerKey)
            );
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerReadonlyIntProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                $this->generateIntGetter($containerKey),
                null
            );
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerNullableIntProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                $this->generateNullableIntGetter($containerKey),
                $this->generateNullableIntSetter($containerKey)
            );
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerReadonlyNullableIntProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                $this->generateNullableIntGetter($containerKey),
                null
            );
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerArrayProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                function () use ($containerKey): MutableArray {
                    return $this->dataContainer[$containerKey];
                },
                function ($value) use ($containerKey, $propertyName): void {
                    if (is_array($value)) {
                        $this->dataContainer[$containerKey] = new MutableArray($value);
                    } else if ($value instanceof MutableArray) {
                        $this->dataContainer[$containerKey] = $value;
                    } else {
                        throw new TypeError("Property \${$propertyName} must be of the type array or of the type MutableArray.");
                    }
                }
            );
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerReadonlyArrayProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                function () use ($containerKey): array {
                    /** @var $ma MutableArray */
                    $ma = $this->dataContainer[$containerKey];
                    return $ma->getArrayCopy();
                }, null
            );
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerNullableArrayProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                function () use ($containerKey): ?MutableArray {
                    return $this->dataContainer[$containerKey];
                },
                function ($value) use ($containerKey, $propertyName): void {
                    if (is_array($value)) {
                        $this->dataContainer[$containerKey] = new MutableArray($value);
                    } else if ($value instanceof MutableArray) {
                        $this->dataContainer[$containerKey] = $value;
                    } else if (is_null($value)) {
                        $this->dataContainer[$containerKey] = $value;
                    } else {
                        throw new TypeError("Property \${$propertyName} must be of the type array, of the type MutableArray or null.");
                    }
                }
            );
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param string           $containerKey
     */
    final protected function registerReadonlyNullableArrayProperty(
        PropertyRegister $propertyRegister,
        string $propertyName,
        string $containerKey
    ): void {
        $propertyRegister
            ->newVirtualProperty(
                $propertyName,
                function () use ($containerKey): ?array {
                    $ma = $this->dataContainer[$containerKey];
                    if ($ma instanceof MutableArray) {
                        return $ma->getArrayCopy();
                    } else {
                        return null;
                    }
                }, null
            );
    }

    /**
     * @param string $containerKey
     * @return Closure
     */
    final protected function generateStringGetter(string $containerKey): Closure
    {
        return function () use ($containerKey): string {
            return $this->dataContainer[$containerKey];
        };
    }

    /**
     * @param string $containerKey
     * @return Closure
     */
    final protected function generateStringSetter(string $containerKey): Closure
    {
        return function (string $value) use ($containerKey): void {
            $this->dataContainer[$containerKey] = $value;
        };
    }

    /**
     * @param string $containerKey
     * @return Closure
     */
    final protected function generateIntGetter(string $containerKey): Closure
    {
        return function () use ($containerKey): int {
            return $this->dataContainer[$containerKey];
        };
    }

    /**
     * @param string $containerKey
     * @return Closure
     */
    final protected function generateIntSetter(string $containerKey): Closure
    {
        return function (int $value) use ($containerKey): void {
            $this->dataContainer[$containerKey] = $value;
        };
    }


    /**
     * @param string $containerKey
     * @return Closure
     */
    final protected function generateNullableStringGetter(string $containerKey): Closure
    {
        return function () use ($containerKey): ?string {
            return $this->dataContainer[$containerKey];
        };
    }

    /**
     * @param string $containerKey
     * @return Closure
     */
    final protected function generateNullableStringSetter(string $containerKey): Closure
    {
        return function (?string $value) use ($containerKey): void {
            $this->dataContainer[$containerKey] = $value;
        };
    }

    /**
     * @param string $containerKey
     * @return Closure
     */
    final protected function generateNullableIntGetter(string $containerKey): Closure
    {
        return function () use ($containerKey): ?int {
            return $this->dataContainer[$containerKey];
        };
    }

    /**
     * @param string $containerKey
     * @return Closure
     */
    final protected function generateNullableIntSetter(string $containerKey): Closure
    {
        return function (?int $value) use ($containerKey): void {
            $this->dataContainer[$containerKey] = $value;
        };
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param array            $flippedCandidateList
     */
    final protected function restrictStringCandidates(
        PropertyRegister $propertyRegister,
        string $propertyName,
        array $flippedCandidateList
    ): void {
        $propertyRegister
            ->addSetterHook($propertyName, function (string $value, Closure $next) use ($flippedCandidateList): void {
                if (!isset($flippedCandidateList[$value])) {
                    throw new DomainException;
                }
                $next($value);
            });
    }

    /**
     * @param PropertyRegister $propertyRegister
     * @param string           $propertyName
     * @param int              $min
     * @param int              $max
     */
    final protected function restrictIntRange(
        PropertyRegister $propertyRegister,
        string $propertyName,
        int $min, int $max
    ): void {
        $propertyRegister
            ->addSetterHook($propertyName, function (int $value, Closure $next) use ($min, $max): void {
                if ($value < $min || $max < $value) {
                    throw new DomainException;
                }
                $next($value);
            });
    }
}