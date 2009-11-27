<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4
 * @filesource
 * @package UserInput
 * @subpackage Tests
 */

/**
 * Require the test cases
 */
require_once 'tools_test.php';

/**
 * @package UserInput
 * @subpackage Tests
 */
class ezcUserInputSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName("UserInput");

        $this->addTest( ezcInputFilterDefinitionTest::suite() );
    }

    public static function suite()
    {
        return new ezcUserInputSuite();
    }
}
?>
