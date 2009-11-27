<?php
/**
 * ezcSystemInfo
 *
 * @package SystemInformation
 * @subpackage Tests
 * @version 1.0.7
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Require test suite for SystemInformation class.
 */
require_once 'sysinfo_test.php';

/**
 * Test suite for SystemInformation package.
 *
 * @package SystemInformation
 * @subpackage Tests
 */
class ezcSystemInformationSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "SystemInformation" );
        $this->addTest( ezcSystemInfoTest::suite() );
    }

    public static function suite()
    {
        return new ezcSystemInformationSuite( "ezcSystemInformationSuite" );
    }
}
?>
