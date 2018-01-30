<?php

namespace Bellisq\Request\Containers;

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
{
    public const METHOD_GET    = 'GET';
    public const METHOD_POST   = 'POST';
    public const METHOD_PUT    = 'PUT';
    public const METHOD_DELETE = 'DELETE';

    public const PORT_HTTP = 80;
    public const PORT_HTTPS = 443;

    public const SCHEME_HTTP = 'http';
    public const SCHEME_HTTPS = 'https';

    public const PROTOCOL_HTTP10 = 'HTTP/1.0';
    public const PROTOCOL_HTTP11 = 'HTTP/1.1';
    public const PROTOCOL_HTTP20 = 'HTTP/2.0';

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

        $this->offsetSet(self::VAR_BODY, null);
        $this->offsetSet(self::VAR_PARSED_POST, null);
        $this->offsetSet(self::VAR_PARSED_FILES, null);
        if (self::METHOD_POST === $this->offsetGet(self::VAR_LINE_METHOD)) {
            $this->offsetSet(self::VAR_PARSED_POST, new MutableArray());
            $this->offsetSet(self::VAR_PARSED_FILES, new MutableArray());
        } else if (self::METHOD_PUT === $this->offsetGet(self::VAR_LINE_METHOD)) {
            $this->offsetSet(self::VAR_BODY, '');
        }
    }
}