<?php
/**
 * File containing the table handler
 *
 * @package Document
 * @version 1.1.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Visit table
 * 
 * @package Document
 * @version 1.1.2
 */
class ezcDocumentDocbookToEzXmlTableHandler extends ezcDocumentElementVisitorHandler
{
    /**
     * Handle a node
     *
     * Handle / transform a given node, and return the result of the
     * conversion.
     * 
     * @param ezcDocumentElementVisitorConverter $converter 
     * @param DOMElement $node 
     * @param mixed $root 
     * @return mixed
     */
    public function handle( ezcDocumentElementVisitorConverter $converter, DOMElement $node, $root )
    {
        $paragraph = $root->ownerDocument->createElement( 'paragraph' );
        $root->appendChild( $paragraph );

        $table = $root->ownerDocument->createElement( 'table' );
        $paragraph->appendChild( $table );

        $converter->visitChildren( $node, $table );
        return $root;
    }
}

?>
