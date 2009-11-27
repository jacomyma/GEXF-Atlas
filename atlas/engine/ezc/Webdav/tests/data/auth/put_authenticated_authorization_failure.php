<?php

return array(
    array(
        'server' => array(
            'REQUEST_URI'        => '/a/a2',
            'REQUEST_METHOD'     => 'PUT',
            // foo:bar
            'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==',
            'CONTENT_TYPE'   => 'text/plain; charset="utf-8"',
            'HTTP_CONTENT_LENGTH' => '9',
        ),
        'body' => 'Some text',
    ),
    array(
        'status' => 'HTTP/1.1 403 Forbidden',
        'headers' => array(
            'Server'           => 'eZComponents/dev/ezcWebdavTransportTestMock',
            'Content-Type'     => 'text/plain; charset="utf-8"',
            'Content-Length'   => '21',
        ),
        'body' => 'Authorization failed.',
    ),
);

?>
