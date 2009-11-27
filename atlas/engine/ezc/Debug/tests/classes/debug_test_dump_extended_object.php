<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.2
 * @filesource
 * @package Debug
 * @subpackage Tests
 */

require_once 'debug_test_dump_object.php';

class DebugTestDumpExtendedObject extends DebugTestDumpObject
{
    private $extendedPrivate;

    protected $extendedProtected;

    public $extendedPublic;

    public function __construct( $private, $protected, $public, $extendedPrivate, $extendedProtected, $extendedPublic )
    {
        $this->extendedPrivate   = $extendedPrivate;
        $this->extendedProtected = $extendedProtected;
        $this->extendedPublic    = $extendedPublic;
        parent::__construct( $private, $protected, $public );
    }
}

?>
