<?php
/**
 * File containing the test suite for the Webdav component.
 *
 * @package Webdav
 * @subpackage Tests
 * @version 1.1
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Require test suites.
 */
require_once 'backend_simple_test.php';
require_once 'backend_memory_test.php';
require_once 'backend_file_test.php';
require_once 'backend_file_options_test.php';

require_once 'path_factory_test.php';
require_once 'path_factory_automatic_test.php';

require_once 'property_storage_test.php';
require_once 'property_storage_flagged_test.php';

require_once 'infrastructure_base_test.php';

require_once 'property_dead_test.php';
require_once 'property_creationdate_test.php';
require_once 'property_displayname_test.php';
require_once 'property_getcontentlanguage_test.php';
require_once 'property_getcontentlength_test.php';
require_once 'property_getcontenttype_test.php';
require_once 'property_getetagtest.php';
require_once 'property_getlastmodified_test.php';
require_once 'property_lockdiscovery_activelock_test.php';
require_once 'property_lockdiscovery_test.php';
require_once 'property_resourcetype_test.php';
require_once 'property_source_link_test.php';
require_once 'property_source_test.php';
require_once 'property_supportedlock_lockentry_test.php';
require_once 'property_supportedlock_test.php';

require_once 'request_copy_test.php';
require_once 'request_move_test.php';
require_once 'request_propfind_test.php';
require_once 'request_proppatch_test.php';
require_once 'request_lock_test.php';
require_once 'request_unlock_test.php';

require_once 'request_content_property_behaviour_test.php';

require_once 'response_error_test.php';
require_once 'response_get_test.php';
require_once 'response_options_test.php';
require_once 'response_test.php';

require_once 'server_test.php';
require_once 'server_configuration_test.php';
require_once 'server_configuration_manager_test.php';
require_once 'server_options_test.php';
require_once 'server_auth_test.php';

require_once 'authenticator_test.php';

require_once 'header_handler_test.php';
require_once 'transport_test.php';

require_once 'plugin_configuration_test.php';
require_once 'plugin_registry_test.php';

require_once 'client_test_rfc.php';
require_once 'client_test_rfc_lock.php';
require_once 'client_test_litmus.php';
require_once 'client_test_litmus_lock.php';
require_once 'client_test_cadaver.php';
require_once 'client_test_cadaver_lock.php';
require_once 'client_test_nautilus.php';
require_once 'client_test_nautilus_new.php';
require_once 'client_test_konqueror.php';
require_once 'client_test_ie6.php';
require_once 'client_test_ie6_auth.php';
require_once 'client_test_ie7.php';
require_once 'client_test_ie7_auth.php';
require_once 'client_test_bitkinex.php';

require_once 'lock_plugin_options_test.php';
require_once 'lock_if_header_list_item_test.php';
require_once 'lock_if_header_tagged_list_test.php';
require_once 'lock_if_header_no_tag_list_test.php';
require_once 'lock_header_handler_test.php';
require_once 'lock_property_handler_test.php';
require_once 'lock_administrator_test.php';

/**
 * Test suite for the Webdav component.
 *
 * @package Webdav
 * @subpackage Tests
 * @version 1.1
 */
class ezcWebdavSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( 'Webdav' );

        $this->addTest( ezcWebdavBasicServerTest::suite() );
        $this->addTest( ezcWebdavServerOptionsTest::suite() );
        
        $this->addTest( ezcWebdavHeaderHandlerTest::suite() );
        $this->addTest( ezcWebdavTransportTest::suite() );

        $this->addTest( ezcWebdavPluginConfigurationTest::suite() );
        $this->addTest( ezcWebdavPluginRegistryTest::suite() );

        $this->addTest( ezcWebdavServerConfigurationTest::suite() );
        $this->addTest( ezcWebdavServerConfigurationManagerTest::suite() );

        $this->addTest( ezcWebdavServerAuthTest::suite() );
        $this->addTest( ezcWebdavAuthenticatorTest::suite() );

        $this->addTest( ezcWebdavFlaggedPropertyStorageTest::suite() );
        $this->addTest( ezcWebdavPropertyStorageTest::suite() );

        $this->addTest( ezcWebdavInfrastructureBaseTest::suite() );

        $this->addTest( ezcWebdavCreationDatePropertyTest::suite() );
        $this->addTest( ezcWebdavDeadPropertyTest::suite() );
        $this->addTest( ezcWebdavDisplayNamePropertyTest::suite() );
        $this->addTest( ezcWebdavGetContentLanguagePropertyTest::suite() );
        $this->addTest( ezcWebdavGetContentLengthPropertyTest::suite() );
        $this->addTest( ezcWebdavGetContentTypePropertyTest::suite() );
        $this->addTest( ezcWebdavGetEtagPropertyTest::suite() );
        $this->addTest( ezcWebdavGetLastModifiedPropertyTest::suite() );
        $this->addTest( ezcWebdavLockDiscoveryPropertyActiveLockTest::suite() );
        $this->addTest( ezcWebdavLockDiscoveryPropertyTest::suite() );
        $this->addTest( ezcWebdavResourceTypePropertyTest::suite() );
        $this->addTest( ezcWebdavSourcePropertyLinkTest::suite() );
        $this->addTest( ezcWebdavSourcePropertyTest::suite() );
        $this->addTest( ezcWebdavSupportedLockPropertyLockentryTest::suite() );
        $this->addTest( ezcWebdavSupportedLockPropertyTest::suite() );

        $this->addTest( ezcWebdavCopyRequestTest::suite() );
        $this->addTest( ezcWebdavLockRequestTest::suite() );
        $this->addTest( ezcWebdavMoveRequestTest::suite() );
        $this->addTest( ezcWebdavPropFindRequestTest::suite() );
        $this->addTest( ezcWebdavPropPatchRequestTest::suite() );
        $this->addTest( ezcWebdavUnlockRequestTest::suite() );
        $this->addTest( ezcWebdavRequestPropertyBehaviourContentTest::suite() );
        
        $this->addTest( ezcWebdavErrorResponseTest::suite() );
        $this->addTest( ezcWebdavGetResponseTest::suite() );
        $this->addTest( ezcWebdavOptionsResponseTest::suite() );
        $this->addTest( ezcWebdavResponseTest::suite() );

        $this->addTest( ezcWebdavSimpleBackendTest::suite() );
        $this->addTest( ezcWebdavMemoryBackendTest::suite() );
        $this->addTest( ezcWebdavFileBackendTest::suite() );
        $this->addTest( ezcWebdavFileBackendOptionsTestCase::suite() );

        $this->addTest( ezcWebdavBasicPathFactoryTest::suite() );
        $this->addTest( ezcWebdavAutomaticPathFactoryTest::suite() );

        $this->addTest( ezcWebdavLockPluginOptionsTest::suite() );
        $this->addTest( ezcWebdavLockIfHeaderListItemTest::suite() );
        $this->addTest( ezcWebdavLockIfHeaderTaggedListTest::suite() );
        $this->addTest( ezcWebdavLockIfHeaderNoTagListTest::suite() );
        $this->addTest( ezcWebdavLockHeaderHandlerTest::suite() );
        $this->addTest( ezcWebdavLockPropertyHandlerTest::suite() );
        $this->addTest( ezcWebdavLockAdministratorTest::suite() );

        $this->addTest( ezcWebdavClientRfcTest::suite() );
        $this->addTest( ezcWebdavClientRfcLockTest::suite() );
        $this->addTest( ezcWebdavClientLitmusTest::suite() );
        $this->addTest( ezcWebdavClientLitmusLockTest::suite() );
        $this->addTest( ezcWebdavClientCadaverTest::suite() );
        $this->addTest( ezcWebdavClientCadaverLockTest::suite() );
        $this->addTest( ezcWebdavClientNautilusTest::suite() );
        $this->addTest( ezcWebdavClientNautilusNewTest::suite() );
        $this->addTest( ezcWebdavClientKonquerorTest::suite() );
        $this->addTest( ezcWebdavClientIE6Test::suite() );
        $this->addTest( ezcWebdavClientIE6AuthTest::suite() );
        $this->addTest( ezcWebdavClientIE7Test::suite() );
        $this->addTest( ezcWebdavClientIE7AuthTest::suite() );
        $this->addTest( ezcWebdavClientBitKinexTest::suite() );
    }

    public static function suite()
    {
        return new ezcWebdavSuite( 'ezcWebdavSuite' );
    }
}
?>
