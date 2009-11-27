<?php
/**
 * File containing the ezcDocumentRstMarkupInterpretedTextNode struct
 *
 * @package Document
 * @version 1.1.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * The inline interpreted text markup AST node
 * 
 * @package Document
 * @version 1.1.2
 * @access private
 */
class ezcDocumentRstMarkupInterpretedTextNode extends ezcDocumentRstMarkupNode
{
    /**
     * Text role
     * 
     * @var mixed
     */
    public $role = false;

    /**
     * Construct RST document node
     * 
     * @param ezcDocumentRstToken $token
     * @param bool $open
     * @return void
     */
    public function __construct( ezcDocumentRstToken $token, $open )
    {
        parent::__construct( $token, self::MARKUP_INTERPRETED );
        $this->openTag = (bool) $open;
    }

    /**
     * Return node content, if available somehow
     * 
     * @return string
     */
    protected function content()
    {
        return (string) $this->role;
    }

    /**
     * Set state after var_export
     * 
     * @param array $properties 
     * @return void
     * @ignore
     */
    public static function __set_state( $properties )
    {
        $node = new ezcDocumentRstMarkupInterpretedTextNode(
            $properties['token'],
            $properties['openTag']
        );

        if ( isset( $properties['role'] ) )
        {
            $node->role = $properties['role'];
        }

        $node->nodes = $properties['nodes'];
        return $node;
    }
}

?>
