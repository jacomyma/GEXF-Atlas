<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.3.2
 * @filesource
 * @package Template
 * @subpackage Tests
 */

/**
 * @package Template
 * @subpackage Tests
 */
class ezcTemplateXhtmlContextTest extends ezcTestCase
{
    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcTemplateXhtmlContextTest" );
    }

    /**
     * Test passing constructor values
     */
    public function testInit()
    {
        $context = new ezcTemplateXhtmlContext;

        self::assertSame( 'xhtml', $context->identifier(),
                          "Method <identifier> does not return correct value." );
    }
}

?>
