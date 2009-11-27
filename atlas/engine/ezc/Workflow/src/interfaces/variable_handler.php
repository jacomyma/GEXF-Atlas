<?php
/**
 * File containing the ezcWorkflowVariableHandler interface.
 *
 * @package Workflow
 * @version 1.3.3
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Interface for variable handlers.
 *
 * @package Workflow
 * @version 1.3.3
 */
interface ezcWorkflowVariableHandler
{
    /**
     * Load the variable $variableName that is handled by this handler.
     *
     * @param ezcWorkflowExecution $execution
     * @param string               $variableName
     */
    public function load( ezcWorkflowExecution $execution, $variableName );

    /**
     * Save the variable $variableName that is handled by the variable handler
     * with the value $value.
     *
     * @param ezcWorkflowExecution $execution
     * @param string               $variableName
     * @param mixed                $value
     */
    public function save( ezcWorkflowExecution $execution, $variableName, $value );
}
?>
