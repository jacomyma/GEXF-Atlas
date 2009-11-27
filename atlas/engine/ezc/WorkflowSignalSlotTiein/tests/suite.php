<?php
/**
 * @package WorkflowSignalSlotTiein
 * @subpackage Tests
 * @version 1.0
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'plugin_test.php';

/**
 * @package WorkflowSignalSlotTiein
 * @subpackage Tests
 */
class ezcWorkflowSignalSlotTieinSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( 'WorkflowSignalSlotTiein' );

        $this->addTest( ezcWorkflowSignalSlotTieinPluginTest::suite() );
    }

    public static function suite()
    {
        return new ezcWorkflowSignalSlotTieinSuite;
    }
}
?>
