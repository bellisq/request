<?php

namespace Bellisq\Request\Containers;

use Bellisq\Request\Containers\DataContainerAccessor;
use Strict\Property\Intermediate\PropertyRegister;


/**
 * [Class] HTTP Parsed Variables (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 *
 * @property-read array      $get
 * @property-read array|null $post
 * @property-read array|null $files
 * @property-read array      $cookie
 */
class HttpParsedVariablesImmutable
    extends DataContainerAccessor
{
    /**
     * @param PropertyRegister $propertyRegister
     */
    protected function registerProperty(PropertyRegister $propertyRegister): void
    {
        $this->registerReadonlyArrayProperty(
            $propertyRegister,
            'get',
            RequestDataContainer::VAR_PARSED_GET
        );
        $this->registerReadonlyNullableArrayProperty(
            $propertyRegister,
            'post',
            RequestDataContainer::VAR_PARSED_POST
        );
        $this->registerReadonlyNullableArrayProperty(
            $propertyRegister,
            'files',
            RequestDataContainer::VAR_PARSED_FILES
        );
        $this->registerReadonlyArrayProperty(
            $propertyRegister,
            'cookie',
            RequestDataContainer::VAR_PARSED_COOKIE
        );
    }
}