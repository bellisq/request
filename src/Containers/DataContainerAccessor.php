<?php

namespace Bellisq\Request\Containers;

use Closure;
use DomainException;
use Strict\Property\Utility\StrictPropertyContainer;
use Strict\Property\Intermediate\PropertyRegister;


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