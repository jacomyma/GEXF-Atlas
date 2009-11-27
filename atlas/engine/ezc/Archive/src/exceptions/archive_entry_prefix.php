<?php
/**
 * File containing the ezcArchiveEntryPrefixException class.
 *
 * @package Archive
 * @version 1.3.3
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * An exception for an invalid prefix of a file entry.
 *
 * @package Archive
 * @version 1.3.3
 */
class ezcArchiveEntryPrefixException extends ezcArchiveException
{
    /**
     * Constructs a new entry prefix exception for the specified file entry.
     *
     * @param string $prefix
     * @param string $fileName
     */
    public function __construct( $prefix, $fileName )
    {
        parent::__construct( "The prefix '{$prefix}' from the file entry '{$fileName}' is invalid." );
    }
}
?>
