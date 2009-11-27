<?php
/**
 * File containing the ezcWorkflowVisitorNodeCounter class.
 *
 * @package Workflow
 * @version 1.3.3
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Visitor that counts the number of nodes in a workflow.
 *
 * @package Workflow
 * @version 1.3.3
 * @ignore
 */
class ezcWorkflowVisitorNodeCounter implements ezcWorkflowVisitor
{
    /**
     * Holds the visited nodes.
     *
     * @var array(ezcWorkflowVisitable)
     */
    protected $nodes = array();

    /**
     * Constructor.
     *
     * @param ezcWorkflow $workflow
     */
    public function __construct( ezcWorkflow $workflow )
    {
        $workflow->accept( $this );
    }

    /**
     * Visits the node, adds it to the list of nodes.
     *
     * Returns true if the node was added. False if it was already in the list
     * of nodes.
     *
     * @param ezcWorkflowVisitable $visitable
     * @return boolean
     */
    public function visit( ezcWorkflowVisitable $visitable )
    {
        if ( $visitable instanceof ezcWorkflowNode )
        {
            foreach( $this->nodes as $node )
            {
                if ( $node === $visitable )
                {
                    return false;
                }
            }

            $this->nodes[] = $visitable;
        }

        return true;
    }

    /**
     * Returns the maximum node id.
     *
     * @return array
     */
    public function getNumNodes()
    {
        return count( $this->nodes );
    }
}
?>
