<?php

namespace Bellisq\Request\Containers;

use Strict\Property\Intermediate\PropertyRegister;


/**
 * [Class] HTTP Remote Info (Immutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 *
 * @property-read string $address
 * @property-read int    $port
 */
class HttpRemoteInfoImmutable
    extends DataContainerAccessor
{
    /**
     * @param PropertyRegister $propertyRegister
     */
    protected function registerProperty(PropertyRegister $propertyRegister): void
    {
        $this->registerReadonlyStringProperty(
            $propertyRegister,
            'address',
            RequestDataContainer::VAR_REMOTE_ADDRESS
        );
        $this->registerReadonlyIntProperty(
            $propertyRegister,
            'port',
            RequestDataContainer::VAR_REMOTE_PORT
        );
    }
}