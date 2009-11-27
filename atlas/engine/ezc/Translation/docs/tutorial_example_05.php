<?php
require_once 'tutorial_autoload.php';

$cacheObj = new ezcCacheStorageFileArray( dirname( __FILE__ ). '/translations-cache' );
$backend = new ezcTranslationCacheBackend( $cacheObj );

$manager = new ezcTranslationManager( $backend );
$headersContext = $manager->getContext( 'nb_NO', 'tutorial/headers' );
$descriptionContext = $manager->getContext( 'nb_NO', 'tutorial/descriptions' );

echo $headersContext->getTranslation( 'header1' ), "\n";
echo $descriptionContext->getTranslation( 'desc1' ), "\n";
echo $descriptionContext->getTranslation( 'desc2' ), "\n";
?>
