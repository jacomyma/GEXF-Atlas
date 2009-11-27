<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version 1.3.3
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowTestVariableHandler implements ezcWorkflowVariableHandler
{
    protected $storage = array( 'foo' => 'bar' );

    public function load( ezcWorkflowExecution $execution, $variableName )
    {
        return $this->storage[$variableName];
    }

    public function save( ezcWorkflowExecution $execution, $variableName, $value )
    {
        $this->storage[$variableName] = $value;
    }
}
?>
