<?php
/**
 * File containing the ezcSearchNetworkException class
 *
 * @package Search
 * @version 1.0.4
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Exception thrown when a network connection to a search backends gets
 * disconnected permaturely.
 *
 * @package Search
 * @version 1.0.4
 */
class ezcSearchNetworkException extends ezcSearchException
{
    /**
     * Contains the raw HTTP body of the network error, if available
     *
     * @var string
     */
    public $rawBody;

    /**
     * Constructs an ezcSearchNetworkException
     *
     * @param string $message
     */
    public function __construct( $message, $rawBody = null )
    {
        $this->rawBody = $rawBody;
        $message = "A network issue occurred: $message";
        parent::__construct( $message );
    }
}
?>
