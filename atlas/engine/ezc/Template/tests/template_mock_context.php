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
 * Sample implementation of an output context, this tests that there are no
 * interface functions which are not implemented.
 *
 * @package Template
 * @subpackage Tests
 */
class ezcTemplateMockContext implements ezcTemplateOutputContext
{
    public function cleanupWhitespace()
    {
        return false;
    }

    public function cleanupEol()
    {
        return false;
    }

    public function indent()
    {
        return false;
    }

    public function transformOutput( ezcTemplateAstNode $node )
    {
        return false;
    }

    public function identifier()
    {
        return 'mock';
    }
}
?>
