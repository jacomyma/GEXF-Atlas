<?php
/**
 * ezcCacheStorageFileEvalArrayTest 
 * 
 * @package Cache
 * @subpackage Tests
 * @version 1.4.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Require parent test class. 
 */
require_once 'storage_test.php';

/**
 * Test suite for ezcStorageFileEvalArray class. 
 * 
 * @package Cache
 * @subpackage Tests
 */
class ezcCacheStorageFileEvalArrayTest extends ezcCacheStorageTest
{
	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcCacheStorageFileEvalArrayTest" );
	}
}
?>
