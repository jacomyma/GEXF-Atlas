<?php
/**
 * ezcPersistentObjectDatabaseSchemaTieinSuite
 * 
 * @package PersistentObjectDatabaseSchemaTiein
 * @subpackage Tests
 * @version 1.2.1
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once dirname( __FILE__ ) . "/test.php";

/**
 * Test suite for PersistentObjectDatabaseSchemaTiein package.
 * 
 * @package PersistentObjectDatabaseSchemaTiein
 * @subpackage Tests
 */
class ezcPersistentObjectDatabaseSchemaTieinSuite extends PHPUnit_Framework_TestSuite
{
	public function __construct()
	{
		parent::__construct();
        $this->setName( "PersistentObjectDatabaseSchemaTiein" );
		$this->addTest( ezcPersistentObjectDatabaseSchemaTieinTest::suite() );
	}

    public static function suite()
    {
        return new ezcPersistentObjectDatabaseSchemaTieinSuite( "ezcPersistentObjectDatabaseSchemaTieinSuite" );
    }
}
?>
