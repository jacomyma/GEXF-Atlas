<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.3
 * @filesource
 * @package Log
 * @subpackage Tests
 */

require_once( "test_classes.php" );

/**
 * @package Log
 * @subpackage Tests
 */
class ezcLogDelayedInitTest extends ezcTestCase
{
    private $dbg;

    public function testDelayedInit()
    {
        ezcBaseInit::setCallback( 'ezcInitLog', 'testDelayedInitLog' );
        $log = ezcLog::getInstance();
        $rule = new ezcLogFilterRule( new ezcLogFilter(), $writer = new ezcLogUnixFileWriter( '/' ), true );
        $expected = new ezcLogFilterSet();
        $expected->appendRule( $rule );
        $this->assertAttributeEquals( $expected, 'writers', $log );
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite("ezcLogDelayedInitTest");
    }
}

?>
