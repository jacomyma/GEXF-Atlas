<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.0
 * @filesource
 * @package MvcTemplateTiein
 * @subpackage Tests
 */

/**
 * Including the tests
 */
require 'views/template.php';

/**
 * @package MvcTemplateTiein
 * @subpackage Tests
 */
class ezcMvcTemplateTieinSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( 'MvcTemplateTiein' );

        $this->addTest( ezcMvcTemplateViewTest::suite() );
    }

    public static function suite()
    {
        return new ezcMvcTemplateTieinSuite();
    }
}

?>
