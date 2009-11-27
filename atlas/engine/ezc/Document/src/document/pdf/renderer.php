<?php
/**
 * File containing the ezcDocumentPdfRenderer class
 *
 * @package Document
 * @version 1.1.2
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @access private
 */

/**
 * Abstract renderer base class
 *
 * @package Document
 * @access private
 * @version 1.1.2
 */
abstract extends ezcDocumentPdfRenderer
{
    /**
     * Used driver implementation
     * 
     * @var ezcDocumentPdfDriver
     */
    protected $driver;

    /**
     * Construct renderer from driver to use
     * 
     * @param ezcDocumentPdfDriver $driver 
     * @return void
     */
    public function __construct( ezcDocumentPdfDriver $driver )
    {
        $this->driver = $driver;
    }
}
?>
