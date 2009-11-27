<?php
class myAddressDirective extends ezcDocumentRstDirective
{
    public function toDocbook( DOMDocument $document, DOMElement $root )
    {
        $address = $document->createElement( 'address' );
        $root->appendChild( $address );

        if ( !empty( $this->node->parameters ) )
        {
            $name = $document->createElement( 'personname', htmlentities( $this->node->parameters ) );
            $address->appendChild( $name );
        }

        if ( isset( $this->node->options['street'] ) )
        {
            $street = $document->createElement( 'street', htmlentities( $this->node->options['street'] ) );
            $address->appendChild( $street );
        }
    }
}
?>
