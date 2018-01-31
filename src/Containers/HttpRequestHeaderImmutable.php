<?php

namespace Bellisq\Request\Containers;

use Strict\Property\Intermediate\PropertyRegister;
use Bellisq\Request\Containers\DataContainerAccessor;
use Bellisq\Request\Containers\RequestDataContainer;


/**
 * [Class] Http Request Header (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 *
 * @property-read string|null $userAgent
 * @property-read array       $acceptLanguage
 * @property-read int|null    $ifModifiedSince
 * @property-read string|null $ifNoneMatch
 * @property-read string|null $referer
 */
class HttpRequestHeaderImmutable
    extends DataContainerAccessor
{
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
            $this->registerReadonlyNullableStringProperty($propertyRegister, $key, $value);
        }

        $this->registerReadonlyNullableIntProperty(
            $propertyRegister,
            'ifModifiedSince',
            RequestDataContainer::VAR_HEADER_IF_MODIFIED_SINCE
        );

        $this->registerReadonlyArrayProperty(
            $propertyRegister,
            'acceptLanguage',
            RequestDataContainer::VAR_HEADER_ACCEPT_LANGUAGE
        );
    }
}