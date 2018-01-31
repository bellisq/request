<?php

namespace Bellisq\Request\Constants;


/**
 * [Interface] Request Constants
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Bellisq. All Rights Reserved.
 * @package bellisq/request
 * @since 1.0.0
 */
interface RequestConstantsInterface
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
}