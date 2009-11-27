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
 * Visitor for RST title reference text roles
 * 
 * @package Document
 * @version 1.1.2
 */
class ezcDocumentRstTitleReferenceTextRole extends ezcDocumentRstTextRole implements ezcDocumentRstXhtmlTextRole
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
        $title= $document->createElement( 'citetitle' );
        $root->appendChild( $title );

        $this->appendText( $title );
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
        $title= $document->createElement( 'cite' );
        $root->appendChild( $title );

        $this->appendText( $title );
    }
}

?>
