<?php
/**
 * File containing the ezcDocumentRstDangerTextRole class
 *
 * @package Document
 * @version 1.1.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Visitor for RST subscript text roles
 * 
 * @package Document
 * @version 1.1.2
 */
class ezcDocumentRstSubscriptTextRole extends ezcDocumentRstTextRole implements ezcDocumentRstXhtmlTextRole
{
    /**
     * Transform text role to docbook
     *
     * Create a docbook XML structure at the text roles position in the
     * document.
     * 
     * @param DOMDocument $document 
     * @param DOMElement $root 
     * @return void
     */
    public function toDocbook( DOMDocument $document, DOMElement $root )
    {
        $subscript = $document->createElement( 'subscript' );
        $root->appendChild( $subscript );

        $this->appendText( $subscript );
    }

    /**
     * Transform text role to HTML
     *
     * Create a XHTML structure at the text roles position in the document.
     * 
     * @param DOMDocument $document 
     * @param DOMElement $root 
     * @return void
     */
    public function toXhtml( DOMDocument $document, DOMElement $root )
    {
        $subscript = $document->createElement( 'sub' );
        $root->appendChild( $subscript );

        $this->appendText( $subscript );
    }
}

?>
