<?php

namespace Bellisq\Request\Containers;

use Strict\Property\Intermediate\PropertyRegister;
use Bellisq\Request\Containers\DataContainerAccessor;
use Bellisq\Request\Containers\MutableArray;


/**
 * [Class] Http Request Header (Mutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 *
 * @property string|null        $userAgent
 * @property MutableArray|array $acceptLanguage read: MutableArray, write: MutableArray|array
 * @property int|null           $ifModifiedSince
 * @property string|null        $ifNoneMatch
 * @property string|null        $referer
 */
class HttpRequestHeaderMutable
    extends DataContainerAccessor
{
    /**
     * HttpRequestHeaderMutable constructor.
     *
     * @param array                $server
     * @param RequestDataContainer $rdc
     */
    public function __construct(array $server, RequestDataContainer $rdc)
    {
        parent::__construct($rdc);

        $this->userAgent = $server['HTTP_USER_AGENT'] ?? null;

        $languages = explode(',', $server['HTTP_ACCEPT_LANGUAGE'] ?? 'en');
        $languagesWithWeight = [];
        foreach ($languages as $lang) {
            $l = explode(';q=', $lang);
            if (!isset($l[1])) {
                $l[1] = '1.0';
            }
            $languagesWithWeight[$l[0]] = (float)$l[1];
        }
        arsort($languagesWithWeight);
        $this->acceptLanguage = array_keys($languagesWithWeight);

        $ims = $server['HTTP_IF_MODIFIED_SINCE'] ?? null;
        if (!is_null($ims)) {
            $ims = strtotime($ims);
        }
        $this->ifModifiedSince = $ims;

        $this->ifNoneMatch = $server['HTTP_IF_NONE_MATCH'] ?? null;

        $this->referer = $server['HTTP_REFERER'] ?? null;

        $this->dataContainer[RequestDataContainer::VAR_HEADER_RAW]
            = array_filter($server, function (string $key): bool {
            return substr($key, 0, 5) === 'HTTP_';
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Set HTTP_*** variables.
     *
     * @param string $name
     * @param string $value
     */
    public function set(string $name, string $value): void
    {
        $this->dataContainer[RequestDataContainer::VAR_HEADER_RAW][$name] = $value;
    }

    /**
     * Extract HTTP_*** variables.
     *
     * @param string $name
     * @return null|string
     */
    public function get(string $name): ?string
    {
        return $this->dataContainer[RequestDataContainer::VAR_HEADER_RAW][$name] ?? null;
    }

    /**
     * @param PropertyRegister $propertyRegister
     */
    protected function registerProperty(PropertyRegister $propertyRegister): void
    {
        $exl = [
            'userAgent'   => RequestDataContainer::VAR_HEADER_USER_AGENT,
            'ifNoneMatch' => RequestDataContainer::VAR_HEADER_IF_NONE_MATCH,
            'referer'     => RequestDataContainer::VAR_HEADER_REFERER,
        ];
        foreach ($exl as $key => $value) {
            $this->registerNullableStringProperty($propertyRegister, $key, $value);
        }

        $this->registerNullableIntProperty(
            $propertyRegister,
            'ifModifiedSince',
            RequestDataContainer::VAR_HEADER_IF_MODIFIED_SINCE
        );

        $this->registerArrayProperty(
            $propertyRegister,
            'acceptLanguage',
            RequestDataContainer::VAR_HEADER_ACCEPT_LANGUAGE
        );
    }
}