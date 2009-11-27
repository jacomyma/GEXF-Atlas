<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.2
 * @filesource
 * @package Debug
 * @subpackage Tests
 */

/**
 * Test formatter used in the test suite.
 *
 * @package Debug
 * @subpackage Tests
 */
class TestReporter implements ezcDebugOutputFormatter
{
	public function generateOutput( array $timerData, array $writerData )
	{
        return array( $timerData, $writerData );
	}
}
?>
