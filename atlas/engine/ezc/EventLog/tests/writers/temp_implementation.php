<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.3
 * @filesource
 * @package EventLog
 * @subpackage Tests
 */

/**
 * Test file for ezcLogFileWriterTest.
 *
 * @package EventLog
 * @subpackage Tests
 */
class TempImplementation extends ezcLogFileWriter
{
    public function __construct($dir, $file = null, $maxSize = 204800, $maxFiles = 3 )
    {
        parent::__construct($dir, $file, $maxSize, $maxFiles);
    }

    public function writeLogMessage( $message, $type, $source, $category, $extraInfo = array() )
    {
        $res = print_r( array( "message" => $message, "type" => $type, "source" => $source, "category" => $category ), true );
        $this->write( $type, $source, $category, $res );
    }

    public function openFile( $fileName )
    {
        return parent::openFile( $fileName );
    }

}
?>
