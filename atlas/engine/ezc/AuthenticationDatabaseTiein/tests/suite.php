<?php
/**
 * File containing the ezcAuthenticationDatabaseTieinSuite class.
 *
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @filesource
 * @package AuthenticationDatabaseTiein
 * @version 1.1
 * @subpackage Tests
 */

/**
 * Including the tests
 */
require_once( "filters/database/database_test.php" );
require_once( "filters/openid/openid_db_store_test.php" );

/**
 * @package AuthenticationDatabaseTiein
 * @version 1.1
 * @subpackage Tests
 */
class ezcAuthenticationDatabaseTieinSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "AuthenticationDatabaseTiein" );
        
        $this->addTest( ezcAuthenticationDatabaseTest::suite() );
        $this->addTest( ezcAuthenticationOpenidDbStoreTest::suite() );
    }

    public static function suite()
    {
        return new ezcAuthenticationDatabaseTieinSuite();
    }
}
?>
