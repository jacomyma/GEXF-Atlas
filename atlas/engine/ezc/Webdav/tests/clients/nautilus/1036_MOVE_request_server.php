<?php

return array (
  'LANG' => 'en_US.UTF-8',
  'SERVER_SOFTWARE' => 'lighttpd/1.4.19',
  'SERVER_NAME' => 'webdav',
  'GATEWAY_INTERFACE' => 'CGI/1.1',
  'SERVER_PORT' => '80',
  'SERVER_ADDR' => '127.0.0.1',
  'REMOTE_PORT' => '33458',
  'REMOTE_ADDR' => '127.0.0.1',
  'SCRIPT_NAME' => '/index.php',
  'PATH_INFO' => '/secure_collection/subdir/put_test_non_utf8.txt',
  'PATH_TRANSLATED' => '/home/dotxp/web/webdav/htdocs/secure_collection/subdir/put_test_non_utf8.txt',
  'SCRIPT_FILENAME' => '/home/dotxp/web/webdav/htdocs/index.php',
  'DOCUMENT_ROOT' => '/home/dotxp/web/webdav/htdocs/',
  'REQUEST_URI' => '/secure_collection/subdir/put_test_non_utf8.txt',
  'REDIRECT_URI' => '/index.php/secure_collection/subdir/put_test_non_utf8.txt',
  'QUERY_STRING' => '',
  'REQUEST_METHOD' => 'MOVE',
  'REDIRECT_STATUS' => '200',
  'SERVER_PROTOCOL' => 'HTTP/1.1',
  'HTTP_HOST' => 'webdav',
  'HTTP_USER_AGENT' => 'gnome-vfs/2.20.0 neon/0.25.4',
  'HTTP_CONNECTION' => 'TE',
  'HTTP_TE' => 'trailers',
  'HTTP_DESTINATION' => 'http://webdav/secure_collection/subdir/put_test_%C3%B6%C3%A4%C3%BC%C3%9F.txt',
  'HTTP_OVERWRITE' => 'F',
  'HTTP_AUTHORIZATION' => 'Digest username="some", realm="eZ Components WebDAV", nonce="32a6c0d48e1a197f0a0db503a6fded39", uri="/secure_collection/subdir/put_test_non_utf8.txt", response="26e8be40a1196616b92f653aeaf0d84d", algorithm="MD5"',
  'PHP_SELF' => '/index.php/secure_collection/subdir/put_test_non_utf8.txt',
  'PHP_AUTH_DIGEST' => 'username="some", realm="eZ Components WebDAV", nonce="32a6c0d48e1a197f0a0db503a6fded39", uri="/secure_collection/subdir/put_test_non_utf8.txt", response="26e8be40a1196616b92f653aeaf0d84d", algorithm="MD5"',
  'REQUEST_TIME' => 1220431173,
);

?>