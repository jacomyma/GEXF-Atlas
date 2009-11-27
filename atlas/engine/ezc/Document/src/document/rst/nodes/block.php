<?php
/**
 * File containing the ezcDocumentRstBlockNode struct
 *
 * @package Document
 * @version 1.1.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * The paragraph AST node
 * 
 * @package Document
 * @version 1.1.2
 * @access private
 */
class ezcDocumentRstBlockNode extends ezcDocumentRstNode
{
    /**
     * Paragraph indentation level
     * 
     * @var int
     */
    public $indentation = 0;
}

?>
