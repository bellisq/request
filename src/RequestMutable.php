<?php

namespace Bellisq\Request;

use Bellisq\Request\Constants\RequestConstantsInterface;
use Bellisq\Request\Containers\DataContainerAccessor;
use Bellisq\Request\Containers\HttpParsedVariablesMutable;
use Bellisq\Request\Containers\HttpRemoteInfoMutable;
use Bellisq\Request\Containers\HttpRequestHeaderMutable;
use Bellisq\Request\Containers\HttpRequestLineMutable;
use Bellisq\Request\Containers\MutableArray;
use Bellisq\Request\Containers\RequestDataContainer;
use Strict\Property\Intermediate\PropertyRegister;


/**
 * [Class] Request (Mutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 *
 * @property-read HttpRequestLineMutable     $line
 * @property-read HttpRequestHeaderMutable   $header
 * @property-read HttpRemoteInfoMutable      $remote
 * @property-read HttpParsedVariablesMutable $parsed
 * @property string|null                     $body
 * @property MutableArray|array              $attributes read: MutableArray, write: MutableArray|array
 */
class RequestMutable
    extends DataContainerAccessor
    implements RequestConstantsInterface
{
    /**
     * RequestMutable constructor.
     *
     * @param array|null                $server
     * @param array|null                $get
     * @param array|null                $post
     * @param array|null                $files
     * @param array|null                $cookie
     * @param RequestDataContainer|null $rdc
     */
    public function __construct(
        ?array $server,
        ?array $get,
        ?array $post,
        ?array $files,
        ?array $cookie,
        ?RequestDataContainer $rdc = null
    ) {
        $rdc = $rdc ?? new RequestDataContainer;
        parent::__construct($rdc);

        $this->setPropertyDirectly('line', new HttpRequestLineMutable($server, $rdc));
        $this->setPropertyDirectly('header', new HttpRequestHeaderMutable($server, $rdc));
        $this->setPropertyDirectly('remote', new HttpRemoteInfoMutable($server, $rdc));
        $this->setPropertyDirectly('parsed', new HttpParsedVariablesMutable($get, $post, $files, $cookie, $rdc));

        if ($this->line->method === RequestDataContainer::METHOD_PUT) {
            $this->body = file_get_contents('php://input');
        } else {
            $this->body = null;
        }
        $this->attributes = [];
    }

    /**
     * @param PropertyRegister $propertyRegister
     */
    protected function registerProperty(
        PropertyRegister $propertyRegister
    ): void {
        $propertyRegister
            ->newProperty('line', true, false)
            ->newProperty('header', true, false)
            ->newProperty('remote', true, false)
            ->newProperty('parsed', true, false);

        $this->registerNullableStringProperty($propertyRegister, 'body', RequestDataContainer::VAR_BODY);
        $this->registerArrayProperty($propertyRegister, 'attributes', RequestDataContainer::VAR_ATTRIBUTES);
    }

    /**
     * Magic method.
     */
    public function __clone()
    {
        parent::__clone();
        $rdc = $this->dataContainer = clone $this->dataContainer;

        $this->setPropertyDirectly('line', new HttpRequestLineMutable(null, $rdc));
        $this->setPropertyDirectly('header', new HttpRequestHeaderMutable(null, $rdc));
        $this->setPropertyDirectly('remote', new HttpRemoteInfoMutable(null, $rdc));
        $this->setPropertyDirectly('parsed', new HttpParsedVariablesMutable(null, null, null, null, $rdc));
    }
}