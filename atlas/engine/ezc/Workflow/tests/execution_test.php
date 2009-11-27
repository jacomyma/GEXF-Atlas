<?php
/**
 * @package Workflow
 * @subpackage Tests
 * @version 1.3.3
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'case.php';
require_once 'execution.php';

/**
 * @package Workflow
 * @subpackage Tests
 */
class ezcWorkflowExecutionTest extends ezcWorkflowTestCase
{
    protected $execution;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcWorkflowExecutionTest' );
    }

    protected function setUp()
    {
        parent::setUp();
        $this->execution = new ezcWorkflowTestExecution;
    }

    public function testExecuteStartEnd()
    {
        $this->setUpStartEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteStartEndVariableHandler()
    {
        $this->setUpStartEndVariableHandler();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
        $this->assertEquals( 'bar', $this->execution->getVariable( 'foo' ) );
    }

    public function testExecuteStartInputEnd()
    {
        $this->setUpStartInputEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->setInputVariable( 'variable', 'value' );
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteStartInputEnd2()
    {
        $this->setUpStartInputEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->setInputVariable( 'variable', false );

        try
        {
            $this->execution->start();
        }
        catch ( ezcWorkflowInvalidInputException $e )
        {
            $this->assertTrue( isset( $e->errors ) );
            $this->assertFalse( isset( $e->foo ) );
            $this->assertArrayHasKey( 'variable', $e->errors );
            $this->assertContains( 'is string', $e->errors );

            $this->assertFalse( $this->execution->isCancelled() );
            $this->assertFalse( $this->execution->hasEnded() );
            $this->assertTrue( $this->execution->isResumed() );
            $this->assertFalse( $this->execution->isSuspended() );

            return;
        }

        $this->fail( 'Expected an ezcBaseValueException to be thrown.' );
    }

    public function testExecuteStartInputEnd3()
    {
        $this->setUpStartInputEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->setInputVariable( 'variable', false );

        try
        {
            $this->execution->start();
        }
        catch ( ezcWorkflowInvalidInputException $e )
        {
            try
            {
                $e->errors = array();
            }
            catch ( ezcBasePropertyPermissionException $e )
            {
                return;
            }

            $this->fail( 'Expected an ezcBasePropertyPermissionException to be thrown.' );
        }

        $this->fail( 'Expected an ezcWorkflowInvalidInputException to be thrown.' );
    }

    public function testExecuteStartInputEnd4()
    {
        $this->setUpStartInputEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->setInputVariable( 'variable', false );

        try
        {
            $this->execution->start();
        }
        catch ( ezcWorkflowInvalidInputException $e )
        {
            try
            {
                $foo = $e->foo;
            }
            catch ( ezcBasePropertyNotFoundException $e )
            {
                return;
            }

            $this->fail( 'Expected an ezcBasePropertyNotFoundException to be thrown.' );
        }

        $this->fail( 'Expected an ezcWorkflowInvalidInputException to be thrown.' );
    }

    public function testExecuteStartInputEnd5()
    {
        $this->setUpStartInputEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->setInputVariable( 'variable', false );

        try
        {
            $this->execution->start();
        }
        catch ( ezcWorkflowInvalidInputException $e )
        {
            try
            {
                $e->foo = 'bar';
            }
            catch ( ezcBasePropertyNotFoundException $e )
            {
                return;
            }

            $this->fail( 'Expected an ezcBasePropertyNotFoundException to be thrown.' );
        }

        $this->fail( 'Expected an ezcWorkflowInvalidInputException to be thrown.' );
    }

    public function testExecuteStartInputEnd6()
    {
        $this->setUpStartInputEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariable( 'variable', false );

        try
        {
            $this->execution->start();
        }
        catch ( ezcWorkflowInvalidInputException $e )
        {
            $this->assertTrue( isset( $e->errors ) );
            $this->assertFalse( isset( $e->foo ) );
            $this->assertArrayHasKey( 'variable', $e->errors );
            $this->assertContains( 'is string', $e->errors );

            $this->assertFalse( $this->execution->isCancelled() );
            $this->assertFalse( $this->execution->hasEnded() );
            $this->assertFalse( $this->execution->isResumed() );
            $this->assertFalse( $this->execution->isSuspended() );

            return;
        }

        $this->fail( 'Expected an ezcWorkflowInvalidInputException to be thrown.' );
    }

    public function testExecuteStartSetUnsetEnd()
    {
        $this->setUpStartSetUnsetEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
        $this->assertFalse( $this->execution->hasVariable( 'x' ) );
    }

    public function testExecuteStartSetUnsetEnd2()
    {
        $plugin = $this->getMock( 'ezcWorkflowExecutionPlugin', array( 'beforeVariableUnset' ) );
        $plugin->expects( $this->any() )
               ->method( 'beforeVariableUnset' )
               ->will( $this->returnValue( false ) );

        $this->setUpStartSetUnsetEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->addPlugin( $plugin );
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
        $this->assertTrue( $this->execution->hasVariable( 'x' ) );
    }

    public function testExecuteIncrementingLoop()
    {
        $this->setUpLoop( 'increment' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteDecrementingLoop()
    {
        $this->setUpLoop( 'decrement' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteSetAddSubMulDiv()
    {
        $this->setUpSetAddSubMulDiv();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
        $this->assertEquals( 1, $this->execution->getVariable( 'x' ) );
    }

    public function testExecuteAddVariables()
    {
        $this->setUpAddVariables();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
        $this->assertEquals( 2, $this->execution->getVariable( 'b' ) );
    }

    public function testExecuteAddVariables2()
    {
        $this->setUpAddVariables2();
        $this->execution->workflow = $this->workflow;

        try
        {
            $this->execution->start();
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            $this->assertEquals( 'Illegal operand.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowExecutionException to be thrown.' );
    }

    public function testExecuteAddVariables3()
    {
        $this->setUpAddVariables3();
        $this->execution->workflow = $this->workflow;

        try
        {
            $this->execution->start();
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            $this->assertEquals( 'Variable "b" is not a number.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowExecutionException to be thrown.' );
    }

    public function testExecuteVariableEqualsVariable()
    {
        $this->setUpVariableEqualsVariable();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
        $this->assertEquals( 1, $this->execution->getVariable( 'c' ) );
    }

    public function testExecuteParallelSplitSynchronization()
    {
        $this->setUpParallelSplitSynchronization();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteParallelSplitSynchronization2()
    {
        $this->setUpParallelSplitSynchronization2();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'foo' => 'bar', 'bar' => 'foo' ) );
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteParallelSplitInvalidSynchronization()
    {
        $this->setUpParallelSplitInvalidSynchronization();
        $this->execution->workflow = $this->workflow;

        try
        {
            $this->execution->start();
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            $this->assertEquals(
              'Cannot synchronize threads that were started by different branches.',
              $e->getMessage()
            );

            return;
        }

        $this->fail( 'Expected an ezcWorkflowExecutionException to be thrown.' );
    }

    public function testExecuteExclusiveChoiceSimpleMerge()
    {
        $this->setUpExclusiveChoiceSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => true ) );
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteExclusiveChoiceSimpleMerge2()
    {
        $this->setUpExclusiveChoiceSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => false ) );
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteExclusiveChoiceSimpleMerge3()
    {
        $this->setUpExclusiveChoiceSimpleMerge( 'ezcWorkflowConditionIsTrue', 'ezcWorkflowConditionIsTrue' );
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => false ) );

        try
        {
            $this->execution->start();
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            $this->assertEquals(
              'Node activates less conditional outgoing nodes than required.',
              $e->getMessage()
            );

            return;
        }

        $this->fail( 'Expected an ezcWorkflowExecutionException to be thrown.' );
    }

    public function testExecuteExclusiveChoiceWithElseSimpleMerge()
    {
        $this->setUpExclusiveChoiceWithElseSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => true ) );
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
        $this->assertEquals( true, $this->execution->getVariable( 'x' ) );
    }

    public function testExecuteExclusiveChoiceWithElseSimpleMerge2()
    {
        $this->setUpExclusiveChoiceWithElseSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => false ) );
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
        $this->assertEquals( true, $this->execution->getVariable( 'y' ) );
    }

    public function testExecuteExclusiveChoiceWithUnconditionalOutNodeSimpleMerge()
    {
        $this->setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => false ) );
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );

        $this->assertTrue( $this->execution->getVariable( 'y' ) );
        $this->assertTrue( $this->execution->getVariable( 'z' ) );
    }

    public function testExecuteExclusiveChoiceWithUnconditionalOutNodeSimpleMerge2()
    {
        $this->setUpExclusiveChoiceWithUnconditionalOutNodeSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->setVariables( array( 'condition' => true ) );
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );

        $this->assertTrue( $this->execution->getVariable( 'x' ) );
        $this->assertTrue( $this->execution->getVariable( 'z' ) );
    }

    public function testExecuteNestedExclusiveChoiceSimpleMerge()
    {
        $this->setUpNestedExclusiveChoiceSimpleMerge();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );

        $this->assertTrue( $this->execution->getVariable( 'x' ) );
        $this->assertTrue( $this->execution->getVariable( 'y' ) );
        $this->assertTrue( $this->execution->getVariable( 'z' ) );
    }

    public function testExecuteNestedExclusiveChoiceSimpleMerge2()
    {
        $this->setUpNestedExclusiveChoiceSimpleMerge( true, false );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );

        $this->assertTrue( $this->execution->getVariable( 'x' ) );
        $this->assertFalse( $this->execution->getVariable( 'y' ) );
        $this->assertFalse( $this->execution->getVariable( 'z' ) );
    }

    public function testExecuteNestedExclusiveChoiceSimpleMerge3()
    {
        $this->setUpNestedExclusiveChoiceSimpleMerge( false );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );

        $this->assertFalse( $this->execution->getVariable( 'x' ) );
        $this->assertFalse( $this->execution->getVariable( 'z' ) );
    }

    public function testExecuteMultiChoiceSynchronizingMerge()
    {
        $this->setUpMultiChoice( 'SynchronizingMerge' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteMultiChoiceDiscriminator()
    {
        $this->setUpMultiChoice( 'Discriminator' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteNonInteractiveSubWorkflow()
    {
        $this->setUpWorkflowWithSubWorkflow( 'StartEnd' );
        $this->execution->definitionStorage = $this->xmlStorage;
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteNonInteractiveSubWorkflow2()
    {
        $this->setUpWorkflowWithSubWorkflow( 'StartEnd' );
        $this->execution->workflow = $this->workflow;

        try
        {
            $this->execution->start();
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            $this->assertEquals(
              'No ezcWorkflowDefinitionStorage implementation available.',
              $e->getMessage()
            );

            return;
        }

        $this->fail( 'Expected an ezcWorkflowExecutionException to be thrown.' );
    }

    public function testExecuteInteractiveSubWorkflow()
    {
        $this->setUpWorkflowWithSubWorkflow( 'StartInputEnd' );
        $this->execution->definitionStorage = $this->xmlStorage;
        $this->execution->workflow = $this->workflow;
        $this->execution->setInputVariableForSubWorkflow( 'variable', 'value' );
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteWorkflowWithCancelCaseSubWorkflow()
    {
        $this->setUpWorkflowWithSubWorkflow( 'ParallelSplitActionActionCancelCaseSynchronization' );
        $this->execution->definitionStorage = $this->xmlStorage;
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertTrue( $this->execution->isCancelled() );
        $this->assertFalse( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteServiceObjectWithConstructor()
    {
        $this->workflow = $this->xmlStorage->loadByName( 'ServiceObjectWithArguments' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteServiceObjectThatDoesNotFinish()
    {
        $this->workflow = $this->xmlStorage->loadByName( 'ServiceObjectThatDoesNotFinish' );
        $this->execution->workflow = $this->workflow;

        try
        {
            $this->execution->start();
        }
        catch( ezcWorkflowExecutionException $e )
        {
            $this->assertEquals(
              'Workflow is waiting for input data that has not been mocked.',
              $e->getMessage()
            );

            return;
        }

        $this->fail( 'Expected an ezcWorkflowExecutionException to be thrown.' );
    }

    public function testExecuteNestedLoops()
    {
        $this->setUpNestedLoops();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );

        $this->assertEquals( 2, $this->execution->getVariable( 'i' ) );
        $this->assertEquals( 2, $this->execution->getVariable( 'j' ) );
    }

    public function testExecuteWorkflowWithSubWorkflowAndVariablePassing()
    {
        $this->setUpWorkflowWithSubWorkflowAndVariablePassing();
        $this->execution->definitionStorage = $this->xmlStorage;
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );

        $this->assertEquals( 1, $this->execution->getVariable( 'x' ) );
        $this->assertEquals( 2, $this->execution->getVariable( 'z' ) );
    }

    public function testExecuteParallelSplitCancelCaseActionActionSynchronization()
    {
        $this->setUpCancelCase( 'first' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertTrue( $this->execution->isCancelled() );
        $this->assertFalse( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteParallelSplitActionActionCancelCaseSynchronization()
    {
        $this->setUpCancelCase( 'last' );
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertTrue( $this->execution->isCancelled() );
        $this->assertFalse( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteWorkflowWithFinalActivitiesAfterCancellation()
    {
        $this->setUpWorkflowWithFinalActivitiesAfterCancellation();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertTrue( $this->execution->getVariable( 'finalActivityExecuted' ) );
        $this->assertTrue( $this->execution->isCancelled() );
        $this->assertFalse( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testExecuteApprovalProcess()
    {
        $this->setUpApprovalProcess();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->isCancelled() );
        $this->assertTrue( $this->execution->hasEnded() );
        $this->assertFalse( $this->execution->isResumed() );
        $this->assertFalse( $this->execution->isSuspended() );
    }

    public function testListener()
    {
        $listener = $this->getMock( 'ezcWorkflowExecutionListener' );

        $this->assertFalse( $this->execution->removeListener( $listener ) );

        $this->assertTrue( $this->execution->addListener( $listener ) );
        $this->assertFalse( $this->execution->addListener( $listener ) );

        $this->assertTrue( $this->execution->removeListener( $listener ) );
        $this->assertFalse( $this->execution->removeListener( $listener ) );
    }

    public function testPlugin()
    {
        $plugin = $this->getMock( 'ezcWorkflowExecutionPlugin' );

        $this->assertTrue( $this->execution->addPlugin( $plugin ) );
        $this->assertFalse( $this->execution->addPlugin( $plugin ) );

        $this->assertTrue( $this->execution->removePlugin( $plugin ) );
        $this->assertFalse( $this->execution->removePlugin( $plugin ) );
    }

    public function testNoWorkflowStartRaisesException()
    {
        $execution = new ezcWorkflowExecutionNonInteractive;

        try
        {
            $execution->start();
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            $this->assertEquals( 'No workflow has been set up for execution.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowExecutionException to be thrown.' );
    }

    public function testNoExecutionIdResumeRaisesException()
    {
        $execution = new ezcWorkflowExecutionNonInteractive;

        try
        {
            $execution->resume();
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            $this->assertEquals( 'No execution id given.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowExecutionException to be thrown.' );
    }

    public function testInteractiveWorkflowRaisesException()
    {
        $this->setupEmptyWorkflow();

        $input = new ezcWorkflowNodeInput( array( 'choice' => new ezcWorkflowConditionIsBool ) );

        $this->workflow->startNode->addOutNode( $input );
        $this->workflow->endNode->addInNode( $input );

        $execution = new ezcWorkflowExecutionNonInteractive;

        try
        {
            $execution->workflow = $this->workflow;
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            $this->assertEquals( 'This executer can only execute workflows that have no Input and SubWorkflow nodes.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowExecutionException to be thrown.' );
    }

    public function testGetVariable()
    {
        $this->setUpStartEnd();
        $this->execution->workflow = $this->workflow;
        $this->execution->start();

        $this->assertFalse( $this->execution->hasVariable( 'foo' ) );

        try
        {
            $this->execution->getVariable( 'foo' );
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            $this->assertEquals( 'Variable "foo" does not exist.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowExecutionException to be thrown.' );
    }

    public function testEndNonExistingThread()
    {
        try
        {
            $this->execution->endThread( 0 );
        }
        catch ( ezcWorkflowExecutionException $e )
        {
            $this->assertEquals( 'There is no thread with id #0.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcWorkflowExecutionException to be thrown.' );
    }

    public function testGetSiblingsForNonExistingThread()
    {
        $this->assertFalse( $this->execution->getNumSiblingThreads( 0 ) );
    }

    public function testProperties()
    {
        $execution = new ezcWorkflowExecutionNonInteractive;

        $this->assertTrue( isset( $execution->definitionStorage ) );
        $this->assertTrue( isset( $execution->workflow ) );
        $this->assertFalse( isset( $execution->foo ) );
    }

    public function testProperties2()
    {
        $execution = new ezcWorkflowExecutionNonInteractive;

        try
        {
            $execution->workflow = new StdClass;
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( 'The value \'O:8:"stdClass":0:{}\' that you were trying to assign to setting \'workflow\' is invalid. Allowed values are: ezcWorkflow.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBaseValueException to be thrown.' );
    }

    public function testProperties3()
    {
        $execution = new ezcWorkflowExecutionNonInteractive;

        try
        {
            $foo = $execution->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $this->assertEquals( 'No such property name \'foo\'.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBasePropertyNotFoundException to be thrown.' );
    }

    public function testProperties4()
    {
        $execution = new ezcWorkflowExecutionNonInteractive;

        try
        {
            $execution->foo = null;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $this->assertEquals( 'No such property name \'foo\'.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBasePropertyNotFoundException to be thrown.' );
    }

    public function testProperties5()
    {
        $execution = new ezcWorkflowExecutionNonInteractive;

        try
        {
            $execution->definitionStorage = new StdClass;
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals( 'The value \'O:8:"stdClass":0:{}\' that you were trying to assign to setting \'definitionStorage\' is invalid. Allowed values are: ezcWorkflowDefinitionStorage.', $e->getMessage() );
            return;
        }

        $this->fail( 'Expected an ezcBaseValueException to be thrown.' );
    }
}
?>
