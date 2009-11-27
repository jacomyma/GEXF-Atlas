<?php
/**
 * @copyright Copyright (C) 2005-2007 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.0
 * @filesource
 * @package TreePersistentObjectTiein
 * @subpackage Tests
 */

/**
 * Require the tests
 */
require_once 'Tree/tests/tree.php';
require_once 'po_store.php';

/**
 * @package TreePersistentObjectTiein
 * @subpackage Tests
 */
class ezcTreePersistentObjectTieinSuite extends PHPUnit_Framework_TestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName("TreePersistentObjectTiein");

        $this->addTest( ezcTreePersistentObjectStore::suite() );
    }

    public static function suite()
    {
        return new ezcTreePersistentObjectTieinSuite();
    }
}

?>
