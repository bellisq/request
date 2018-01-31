<?php

namespace Bellisq\Request\Containers;

use Bellisq\Request\Containers\DataContainerAccessor;
use Strict\Property\Intermediate\PropertyRegister;
use Bellisq\Request\Containers\RequestDataContainer;


/**
 * [Class] HTTP Request Line (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 *
 * @property-read string $scheme
 * @property-read string $host
 * @property-read int    $port
 * @property-read string $path
 * @property-read string $query
 * @property-read string $method
 * @property-read string $protocol
 * @property-read string $uri
 */
class HttpRequestLineImmutable
    extends DataContainerAccessor
{
    /**
     * @param PropertyRegister $propertyRegister
     */
    protected function registerProperty(PropertyRegister $propertyRegister): void
    {
        $exl = [
            'scheme'   => RequestDataContainer::VAR_LINE_SCHEME,
            'host'     => RequestDataContainer::VAR_LINE_HOST,
            'path'     => RequestDataContainer::VAR_LINE_PATH,
            'query'    => RequestDataContainer::VAR_LINE_QUERY,
            'method'   => RequestDataContainer::VAR_LINE_METHOD,
            'protocol' => RequestDataContainer::VAR_LINE_PROTOCOL
        ];
        foreach ($exl as $key => $value) {
            $this->registerReadonlyStringProperty($propertyRegister, $key, $value);
        }

        $this->registerReadonlyIntProperty($propertyRegister, 'port', RequestDataContainer::VAR_LINE_PORT);

        $propertyRegister
            ->newVirtualProperty(
                'uri',
                function (): string {
                    $port = ':' . $this->port;
                    if (($this->port === RequestDataContainer::PORT_HTTP && $this->scheme === RequestDataContainer::SCHEME_HTTP) ||
                        ($this->port === RequestDataContainer::PORT_HTTPS && $this->scheme === RequestDataContainer::SCHEME_HTTPS)) {
                        $port = '';
                    }

                    $query = $this->query;
                    $query = ($query === '') ? '' : ('?' . $query);
                    return $this->scheme . '://' . $this->host . $port . $this->path . $query;
                },
                null
            );
    }
}