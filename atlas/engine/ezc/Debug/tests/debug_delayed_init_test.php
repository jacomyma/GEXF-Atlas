<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.2
 * @filesource
 * @package Debug
 * @subpackage Tests
 */

require_once( "test_classes.php" );

/**
 * @package Debug
 * @subpackage Tests
 */
class ezcDebugDelayedInitTest extends ezcTestCase
{
    private $dbg;

    public function testDelayedInit()
    {
        ezcBaseInit::setCallback( 'ezcInitDebug', 'testDelayedInitDebug' );
        $dbg = ezcDebug::getInstance();
        $this->assertAttributeEquals( new TestReporter(), 'formatter', $dbg );
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite("ezcDebugDelayedInitTest");
    }
}

?>
