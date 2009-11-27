<?php
/**
 * File containing the ezcDocumentWikiDefinitionListItemToken struct
 *
 * @package Document
 * @version 1.1.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * Struct for Wiki document definition list item tokens
 * 
 * @package Document
 * @version 1.1.2
 * @access private
 */
class ezcDocumentWikiDefinitionListItemToken extends ezcDocumentWikiBlockMarkupToken
{
    /**
     * List element indentation
     * 
     * @var int
     */
    public $indentation;

    /**
     * Set state after var_export
     * 
     * @param array $properties 
     * @return void
     * @ignore
     */
    public static function __set_state( $properties )
    {
        $tokenClass = __CLASS__;
        $token = new $tokenClass(
            $properties['content'],
            $properties['line'],
            $properties['position']
        );

        // Set additional token values
        $token->indentation = $properties['indentation'];

        return $token;
    }
}

?>
