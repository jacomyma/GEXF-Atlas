<?php
/**
 * File containing the ezcArchiveException class.
 *
 * @package Archive
 * @version 1.3.3
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * General exception class for the Archive package.
 *
 * @package Archive
 * @version 1.3.3
 */
class ezcArchiveException extends ezcBaseException
{
    /**
     * Construct a new archive exception.
     *
     * @param string $message
     */
    public function __construct( $message )
    {
        parent::__construct( $message );
    }
}
?>
