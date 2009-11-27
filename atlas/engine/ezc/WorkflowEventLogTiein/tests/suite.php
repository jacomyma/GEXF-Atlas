<?php
/**
 * @package WorkflowEventLogTiein
 * @subpackage Tests
 * @version 1.1
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'listener_test.php';

/**
 * @package WorkflowEventLogTiein
 * @subpackage Tests
 */
class ezcWorkflowEventLogTieinSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( 'WorkflowEventLogTiein' );

        $this->addTest( ezcWorkflowEventLogTieinListenerTest::suite() );
    }

    public static function suite()
    {
        return new ezcWorkflowEventLogTieinSuite;
    }
}
?>
