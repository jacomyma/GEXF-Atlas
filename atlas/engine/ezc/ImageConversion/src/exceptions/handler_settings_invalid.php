<?php
/**
 * File containing the ezcImageHandlerSettingsInvalidException.
 * 
 * @package ImageConversion
 * @version 1.3.6
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Thrown if invalid handler settings are submitted when creating an
 * {@link ezcImageConverter}.
 *
 * @package ImageConversion
 * @version 1.3.6
 */
class ezcImageHandlerSettingsInvalidException extends ezcImageException
{
    /**
     * Creates a new ezcImageHandlerSettingsInvalidException.
     * 
     * @return void
     */
    function __construct()
    {
        parent::__construct( "Invalid handler settings." );
    }
}

?>
