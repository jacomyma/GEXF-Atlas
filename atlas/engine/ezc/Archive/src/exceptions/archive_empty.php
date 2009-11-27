<?php
/**
 * File containing the ezcArchiveEmptyException class.
 *
 * @package Archive
 * @version 1.3.3
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Exception for when an archive is empty.
 *
 * @package Archive
 * @version 1.3.3
 */
class ezcArchiveEmptyException extends ezcArchiveException
{
    /**
     * Constructs a new exception for empty archive.
     */
    public function __construct()
    {
        parent::__construct( "The archive is empty." );
    }
}
?>
