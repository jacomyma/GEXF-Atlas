<?php

require_once 'classes/transport_test_mock.php';
require_once 'client_test_continuous_setup.php';

require_once 'client_test_suite.php';
require_once 'client_test.php';

class ezcWebdavClientCadaverTest extends ezcWebdavClientTest
{
    protected function setupTestEnvironment()
    {
        $this->setupClass = 'ezcWebdavClientTestContinuousSetup';
        $this->dataDir    = dirname( __FILE__ ) . '/clients/cadaver';
    }

    public static function suite()
    {
        return new ezcWebdavClientTestSuite( __CLASS__ );
    }
}

?>
