<?php

namespace Bellisq\Request\Containers;

use Bellisq\Request\Containers\DataContainerAccessor;
use Strict\Property\Intermediate\PropertyRegister;
use Bellisq\Request\Containers\MutableArray;


/**
 * [Class] HTTP Parsed Variables (Mutable)
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 *
 * @property MutableArray|array      $get
 * @property MutableArray|array|null $post read: MutableArray|null, write: MutableArray|array|null
 * @property MutableArray|array|null $files read: MutableArray|null, write: MutableArray|array|null
 * @property MutableArray|array      $cookie
 */
class HttpParsedVariablesMutable
    extends DataContainerAccessor
{
    /**
     * HttpParsedVariablesMutable constructor.
     *
     * @param array|null           $get
     * @param array|null           $post
     * @param array|null           $files
     * @param array|null           $cookie
     * @param RequestDataContainer $rdc
     */
    public function __construct(
        ?array $get,
        ?array $post,
        ?array $files,
        ?array $cookie,
        RequestDataContainer $rdc
    ) {
        assert(isset($rdc[RequestDataContainer::VAR_LINE_METHOD]));

        parent::__construct($rdc);

        if (is_null($get) && is_null($post) && is_null($files) && is_null($cookie)) {
            return;
        }

        $get = $get ?? [];
        $post = $post ?? [];
        $files = $files ?? [];
        $cookie = $cookie ?? [];

        $this->get = $get;
        if ($rdc[RequestDataContainer::VAR_LINE_METHOD] === RequestDataContainer::METHOD_POST) {
            $this->post = $post;
            $this->files = $files;
        } else {
            $this->post = null;
            $this->files = null;
        }
        $this->cookie = $cookie;
    }

    /**
     * @param PropertyRegister $propertyRegister
     */
    protected function registerProperty(PropertyRegister $propertyRegister): void
    {
        $this->registerArrayProperty(
            $propertyRegister,
            'get',
            RequestDataContainer::VAR_PARSED_GET
        );
        $this->registerNullableArrayProperty(
            $propertyRegister,
            'post',
            RequestDataContainer::VAR_PARSED_POST
        );
        $this->registerNullableArrayProperty(
            $propertyRegister,
            'files',
            RequestDataContainer::VAR_PARSED_FILES
        );
        $this->registerArrayProperty(
            $propertyRegister,
            'cookie',
            RequestDataContainer::VAR_PARSED_COOKIE
        );
    }
}