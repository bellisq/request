<?php

namespace Bellisq\Request\Containers;

use Bellisq\Request\Constants\RequestConstantsInterface;
use Bellisq\Request\Containers\MutableArray;


/**
 * [Class] Request Data Container
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 */
class RequestDataContainer
    extends MutableArray
    implements RequestConstantsInterface
{
    public const VAR_LINE_SCHEME   = 'line.scheme';
    public const VAR_LINE_HOST     = 'line.host';
    public const VAR_LINE_PORT     = 'line.port';
    public const VAR_LINE_PATH     = 'line.path';
    public const VAR_LINE_QUERY    = 'line.query';
    public const VAR_LINE_METHOD   = 'line.method';
    public const VAR_LINE_PROTOCOL = 'line.protocol';

    public const VAR_HEADER_USER_AGENT        = 'header.userAgent';
    public const VAR_HEADER_ACCEPT_LANGUAGE   = 'header.acceptLanguage';
    public const VAR_HEADER_IF_MODIFIED_SINCE = 'header.ifModifiedSince';
    public const VAR_HEADER_IF_NONE_MATCH     = 'header.ifNoneMatch';
    public const VAR_HEADER_REFERER           = 'header.referer';
    public const VAR_HEADER_RAW               = 'header.raw';

    public const VAR_BODY       = 'body';
    public const VAR_ATTRIBUTES = 'attributes';

    public const VAR_REMOTE_ADDRESS = 'remote.address';
    public const VAR_REMOTE_PORT    = 'remote.port';

    public const VAR_PARSED_GET    = 'parsed.get';
    public const VAR_PARSED_COOKIE = 'parsed.cookie';
    public const VAR_PARSED_POST   = 'parsed.post';
    public const VAR_PARSED_FILES  = 'parsed.files';


    /**
     * Remove method-specific attributes.
     */
    public function methodChanged(): void
    {
        assert($this->offsetExists(self::VAR_LINE_METHOD));

        if (self::METHOD_POST === $this->offsetGet(self::VAR_LINE_METHOD)) {
            $this->offsetSet(self::VAR_BODY, null);
            if (is_null($this->offsetGet(self::VAR_PARSED_POST))) {
                $this->offsetSet(self::VAR_PARSED_POST, new MutableArray());
            }
            if (is_null($this->offsetGet(self::VAR_PARSED_FILES))) {
                $this->offsetSet(self::VAR_PARSED_FILES, new MutableArray());
            }
        } else if (self::METHOD_PUT === $this->offsetGet(self::VAR_LINE_METHOD)) {
            if (is_null($this->offsetGet(self::VAR_BODY))) {
                $this->offsetSet(self::VAR_BODY, '');
            }
            $this->offsetSet(self::VAR_PARSED_POST, null);
            $this->offsetSet(self::VAR_PARSED_FILES, null);
        } else {
            $this->offsetSet(self::VAR_BODY, null);
            $this->offsetSet(self::VAR_PARSED_POST, null);
            $this->offsetSet(self::VAR_PARSED_FILES, null);
        }
    }
}