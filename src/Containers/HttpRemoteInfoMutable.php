<?php

namespace Bellisq\Request\Containers;

use Bellisq\Request\Containers\DataContainerAccessor;
use Strict\Property\Intermediate\PropertyRegister;


/**
 * [Class] HTTP Remote Info (Mutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 *
 * @property string $address
 * @property int    $port
 */
class HttpRemoteInfoMutable
    extends DataContainerAccessor
{
    /**
     * HttpRemoteInfoMutable constructor.
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


        $this->address = $server['REMOTE_ADDR'];
        $this->port = (int)$server['REMOTE_PORT'];
    }

    /**
     * @param PropertyRegister $propertyRegister
     */
    protected function registerProperty(PropertyRegister $propertyRegister): void
    {
        $this->registerStringProperty(
            $propertyRegister,
            'address',
            RequestDataContainer::VAR_REMOTE_ADDRESS
        );
        $this->registerIntProperty(
            $propertyRegister,
            'port',
            RequestDataContainer::VAR_REMOTE_PORT
        );
        $this->restrictIntRange(
            $propertyRegister,
            'port',
            0, 65535
        );
    }
}