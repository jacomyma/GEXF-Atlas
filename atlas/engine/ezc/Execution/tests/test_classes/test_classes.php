<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.1.1
 * @filesource
 * @package Execution
 * @subpackage Tests
 */

/**
 * @package Execution
 * @subpackage Tests
 */
class ExecutionTest1
{
}

/**
 * @package Execution
 * @subpackage Tests
 */
class ExecutionTest2 implements ezcExecutionErrorHandler
{
    static public function onError( Exception $e = NULL )
    {
        echo "\nThe ezcExecution succesfully detected an unclean exit.\n";
        echo "Have a nice day!\n";
    }
}
?>
