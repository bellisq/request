<?php

namespace Bellisq\Request\Containers;

use Bellisq\Request\Containers\RequestDataContainer;
use Closure;
use Strict\Property\Intermediate\PropertyRegister;
use Bellisq\Request\Containers\DataContainerAccessor;
use DomainException;


/**
 * [Class] HTTP Request Line (Mutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 *
 * @property string      $scheme
 * @property string      $host
 * @property int         $port
 * @property string      $path
 * @property string      $query
 * @property string      $method
 * @property string      $protocol
 * @property-read string $uri
 */
class HttpRequestLineMutable
    extends DataContainerAccessor
{
    /**
     * HttpRequestLineMutable constructor.
     *
     * @param array|null           $server
     * @param RequestDataContainer $rdc
     */
    public function __construct(?array $server, RequestDataContainer $rdc)
    {
        parent::__construct($rdc);

        if (is_null($server)) {
            return;
        }

        $https = false;
        if (isset($server['HTTP_X_FORWARDED_PROTO'])) {
            $https = ('https' === strtolower($server['HTTP_X_FORWARDED_PROTO']));
        } else if (isset($server['HTTPS'])) {
            $https = ('off' !== strtolower($server['HTTPS']));
        }
        $this->scheme = ($https ? RequestDataContainer::SCHEME_HTTPS : RequestDataContainer::SCHEME_HTTP);

        $this->host = $server['HTTP_HOST'] ?? '';

        if (isset($server['HTTP_X_FORWARDED_PROTO']) || !isset($server['SERVER_PORT'])) {
            $this->port = $https ? RequestDataContainer::PORT_HTTPS : RequestDataContainer::PORT_HTTP;
        } else {
            $this->port = (int)$server['SERVER_PORT'];
        }

        $this->path = explode('?', $server['REQUEST_URI'] ?? '')[0];

        $this->query = $server['QUERY_STRING'] ?? '';

        $this->method = $server['REQUEST_METHOD'] ?? RequestDataContainer::METHOD_GET;

        $this->protocol = $server['SERVER_PROTOCOL'] ?? RequestDataContainer::PROTOCOL_HTTP11;
    }

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
            $this->registerStringProperty($propertyRegister, $key, $value);
        }

        $this->registerIntProperty($propertyRegister, 'port', RequestDataContainer::VAR_LINE_PORT);

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

        $this->restrictStringCandidates($propertyRegister, 'scheme', [
            RequestDataContainer::SCHEME_HTTPS => true,
            RequestDataContainer::SCHEME_HTTP  => true
        ]);

        $this->restrictIntRange($propertyRegister, 'port', 0, 65535);

        $this->restrictStringCandidates($propertyRegister, 'method', [
            RequestDataContainer::METHOD_GET    => true,
            RequestDataContainer::METHOD_POST   => true,
            RequestDataContainer::METHOD_PUT    => true,
            RequestDataContainer::METHOD_DELETE => true,
        ]);
        $propertyRegister
            ->addSetterHook('method', function (string $value, Closure $next): void {
                $next($value);
                $this->dataContainer->methodChanged();
            });

        $this->restrictStringCandidates($propertyRegister, 'protocol', [
            RequestDataContainer::PROTOCOL_HTTP10 => true,
            RequestDataContainer::PROTOCOL_HTTP11 => true,
            RequestDataContainer::PROTOCOL_HTTP20 => true,
        ]);
    }

}