<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.0
 * @filesource
 * @package TemplateTranslationTiein
 * @subpackage Tests
 */

/**
 * Require the tests
 */
require_once 'configuration.php';
require_once 'provider.php';
require_once 'extracter.php';

/**
 * @package TemplateTranslationTiein
 * @subpackage Tests
 */
class ezcTemplateTranslationTieinSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( 'TemplateTranslationTiein' );

        $this->addTest( ezcTemplateTranslationConfigurationTest::suite() );
        $this->addTest( ezcTemplateTranslationProviderTest::suite() );
        $this->addTest( ezcTemplateTranslationExtracterTest::suite() );
    }

    public static function suite()
    {
        return new ezcTemplateTranslationTieinSuite();
    }
}

?>
