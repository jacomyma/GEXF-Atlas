<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.3
 * @filesource
 * @package EventLog
 * @subpackage Tests
 */

require_once( "log_delayed_init_test.php");
require_once( "log_test.php");
require_once( "log_message_test.php");
require_once( "mapper/filterset_test.php");
require_once( "context_test.php");
require_once( "writers/writer_file_test.php");
require_once( "writers/writer_unix_file_test.php");
require_once( "writers/writer_syslog_test.php");

/**
 * @package EventLog
 * @subpackage Tests
 */
class ezcEventLogSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName("EventLog");

        $this->addTest( ezcLogDelayedInitTest::suite() );
        $this->addTest( ezcLogFilterSetTest::suite() );
        $this->addTest( ezcLogContextTest::suite() );
        $this->addTest( ezcLogFileWriterTest::suite() );
        $this->addTest( ezcLogUnixFileWriterTest::suite() );
        $this->addTest( ezcLogSyslogWriterTest::suite() );
        $this->addTest( ezcLogMessageTest::suite() );
        $this->addTest( ezcLogTest::suite() );
    }

    public static function suite()
    {
        return new ezcEventLogSuite();
    }
}


?>
