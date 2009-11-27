<?php
/**
 * A result filter is responsible for altering the result object.
 * 
 * @package MvcTools
 * @version 1.0
 */
interface ezcMvcResultFilter
{
    /**
     * Alters the result object.
     * 
     * @param ezcMvcResult $result Result object to alter.
     * @return void
     */
    public function filterResult( ezcMvcResult $result );
}
?>
