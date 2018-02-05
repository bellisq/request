<?php

namespace Bellisq\Request;

use Bellisq\Request\Constants\RequestConstantsInterface;
use Bellisq\Request\Containers\DataContainerAccessor;
use Bellisq\Request\Containers\HttpParsedVariablesImmutable;
use Bellisq\Request\Containers\HttpRemoteInfoImmutable;
use Bellisq\Request\Containers\HttpRequestHeaderImmutable;
use Bellisq\Request\Containers\HttpRequestLineImmutable;
use Bellisq\Request\Containers\RequestDataContainer;
use Bellisq\Request\RequestMutable;
use Strict\Property\Intermediate\PropertyRegister;


/**
 * [Class] Request (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 *
 * @property-read HttpRequestLineImmutable     $line
 * @property-read HttpRequestHeaderImmutable   $header
 * @property-read HttpRemoteInfoImmutable      $remote
 * @property-read HttpParsedVariablesImmutable $parsed
 * @property-read string|null                  $body
 * @property-read array                        $attributes
 */
class Request
    extends DataContainerAccessor
    implements RequestConstantsInterface
{
    /**
     * @return RequestMutable
     */
    public function getMutable(): RequestMutable
    {
        return new RequestMutable(
            null,
            null,
            null,
            null,
            null,
            clone $this->dataContainer
        );
    }

    /**
     * Request constructor.
     *
     * @param RequestMutable $requestMutable
     */
    public function __construct(RequestMutable $requestMutable)
    {
        $rdc = clone $requestMutable->dataContainer;
        parent::__construct($rdc);

        $this->setPropertyDirectly('line', new HttpRequestLineImmutable($rdc));
        $this->setPropertyDirectly('header', new HttpRequestHeaderImmutable($rdc));
        $this->setPropertyDirectly('remote', new HttpRemoteInfoImmutable($rdc));
        $this->setPropertyDirectly('parsed', new HttpParsedVariablesImmutable($rdc));
    }

    /**
     * @param PropertyRegister $propertyRegister
     */
    protected function registerProperty(PropertyRegister $propertyRegister): void
    {
        $propertyRegister
            ->newProperty('line', true, false)
            ->newProperty('header', true, false)
            ->newProperty('remote', true, false)
            ->newProperty('parsed', true, false);

        $this->registerReadonlyNullableStringProperty(
            $propertyRegister,
            'body',
            RequestDataContainer::VAR_BODY
        );
        $this->registerReadonlyArrayProperty(
            $propertyRegister,
            'attributes',
            RequestDataContainer::VAR_ATTRIBUTES
        );
    }

    /**
     * Magic method.
     */
    public function __clone()
    {
        parent::__clone();
        $rdc = $this->dataContainer = clone $this->dataContainer;

        $this->setPropertyDirectly('line', new HttpRequestLineImmutable($rdc));
        $this->setPropertyDirectly('header', new HttpRequestHeaderImmutable($rdc));
        $this->setPropertyDirectly('remote', new HttpRemoteInfoImmutable($rdc));
        $this->setPropertyDirectly('parsed', new HttpParsedVariablesImmutable($rdc));
    }
}