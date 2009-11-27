<?php

return array(
    array(
        'server' => array(
            'REQUEST_URI'        => '/b/b2',
            'REQUEST_METHOD'     => 'PROPPATCH',
            'HTTP_AUTHORIZATION' => 'Basic c29tZTppbmNvcnJlY3Q=',
        ),
        'body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:propertyupdate xmlns:D="DAV:"
xmlns:Z="http://www.w3.com/standards/z39.50/">
  <D:set>
       <D:prop>
            <Z:authors>
                 <Z:Author>Jim Whitehead</Z:Author>
                 <Z:Author>Roy Fielding</Z:Author>
            </Z:authors>
       </D:prop>
  </D:set>
  <D:remove>
       <D:prop><Z:Copyright-Owner/></D:prop>
  </D:remove>
</D:propertyupdate>
        ',
    ),
    array(
        'status' => 'HTTP/1.1 401 Unauthorized',
        'headers' => array(
            'WWW-Authenticate' => array(
                'basic'  => 'Basic realm="eZ Components WebDAV"',
                'digest' => 'Digest realm="eZ Components WebDAV", nonce="testnonce", algorithm="MD5"',
            ),
            'Server'           => 'eZComponents/dev/ezcWebdavTransportTestMock',
            'Content-Type'     => 'text/plain; charset="utf-8"',
            'Content-Length'   => '22',
        ),
        'body' => 'Authentication failed.',
    ),
);

?>
