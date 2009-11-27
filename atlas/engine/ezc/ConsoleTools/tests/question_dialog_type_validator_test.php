<?php
/**
 * ezcConsoleQuestionDialogTypeValidatorTest class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 * @version 1.5.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Test suite for ezcConsoleQuestionDialogTypeValidator class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 */
class ezcConsoleQuestionDialogTypeValidatorTest extends ezcTestCase
{
	public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( "ezcConsoleQuestionDialogTypeValidatorTest" );
    }

    public function testGetAccessDefaultSuccess()
    {
        $validator = new ezcConsoleQuestionDialogTypeValidator();
        $this->assertEquals( ezcConsoleQuestionDialogTypeValidator::TYPE_STRING, $validator->type );
        $this->assertNull( $validator->default );
    }

    public function testGetAccessCustomSuccess()
    {
        $validator = new ezcConsoleQuestionDialogTypeValidator(
            ezcConsoleQuestionDialogTypeValidator::TYPE_INT,
            23
        );
        $this->assertEquals( ezcConsoleQuestionDialogTypeValidator::TYPE_INT, $validator->type );
        $this->assertEquals( 23, $validator->default );
    }

    public function testGetAccessFailure()
    {
        $validator = new ezcConsoleQuestionDialogTypeValidator();
        
        try
        {
            echo $validator->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid property foo." );
    }

    public function testSetAccessSuccess()
    {
        $validator = new ezcConsoleQuestionDialogTypeValidator();

        $validator->type = ezcConsoleQuestionDialogTypeValidator::TYPE_FLOAT;
        $validator->default = 23.42;

        $this->assertEquals( ezcConsoleQuestionDialogTypeValidator::TYPE_FLOAT, $validator->type );
        $this->assertEquals( 23.42, $validator->default );
    }

    public function testSetAccessFailure()
    {
        $validator = new ezcConsoleQuestionDialogTypeValidator();

        $exceptionCaught = false;
        try
        {
            $validator->type = "Foo";
        }
        catch ( ezcBaseValueException $e )
        {
            $exceptionCaught = true;
        }
        $this->assertTrue( $exceptionCaught, "Exception not thrown on invalid value for property type." );
        
        $exceptionCaught = false;
        try
        {
            $validator->default = array();
        }
        catch ( ezcBaseValueException $e )
        {
            $exceptionCaught = true;
        }
        $this->assertTrue( $exceptionCaught, "Exception not thrown on invalid value for property default." );
        
        $exceptionCaught = false;
        try
        {
            $validator->foo = "Foo";
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $exceptionCaught = true;
        }
        $this->assertTrue( $exceptionCaught, "Exception not thrown on nonexistent property foo." );
    }

    public function testIssetAccess()
    {
        $validator = new ezcConsoleQuestionDialogTypeValidator();
        $this->assertTrue( isset( $validator->type ), "Property collection not set." );
        $this->assertTrue( isset( $validator->default ), "Property default not set." );

        $this->assertFalse( isset( $validator->foo ), "Property foo set." );
    }

    public function testValidate()
    {
        $validator = new ezcConsoleQuestionDialogTypeValidator();

        $this->assertTrue( $validator->validate( "foo" ) );
        $this->assertFalse( $validator->validate( true ) );
        $this->assertFalse( $validator->validate( "" ) );

        $validator->type = ezcConsoleQuestionDialogTypeValidator::TYPE_INT;

        $this->assertTrue( $validator->validate( 23 ) );
        $this->assertFalse( $validator->validate( true ) );
        $this->assertFalse( $validator->validate( "" ) );
        
        $validator->type = ezcConsoleQuestionDialogTypeValidator::TYPE_FLOAT;

        $this->assertTrue( $validator->validate( 23.42 ) );
        $this->assertTrue( $validator->validate( 7E-10 ) );
        $this->assertFalse( $validator->validate( true ) );
        $this->assertFalse( $validator->validate( "" ) );
        
        $validator->type = ezcConsoleQuestionDialogTypeValidator::TYPE_BOOL;

        $this->assertTrue( $validator->validate( true ) );
        $this->assertFalse( $validator->validate( 23.42 ) );
        $this->assertFalse( $validator->validate( "" ) );
        
        $validator->default = "foo";

        $this->assertTrue( $validator->validate( "" ) );
    }

    public function testFixup()
    {
        $validator = new ezcConsoleQuestionDialogTypeValidator();

        $this->assertEquals( "23", $validator->fixup( "23" ) );
        $this->assertEquals( "-23", $validator->fixup( "-23" ) );
        $this->assertEquals( "foo", $validator->fixup( "foo" ) );
        $this->assertEquals( "23.42", $validator->fixup( "23.42" ) );
        $this->assertEquals( "-23.42", $validator->fixup( "-23.42" ) );
        $this->assertEquals( "true", $validator->fixup( "true" ) );
        $this->assertEquals( "false", $validator->fixup( "false" ) );
        $this->assertEquals( "1", $validator->fixup( "1" ) );
        $this->assertEquals( "0", $validator->fixup( "0" ) );
        $this->assertEquals( "", $validator->fixup( "" ) );

        $validator->type = ezcConsoleQuestionDialogTypeValidator::TYPE_INT;
        
        $this->assertEquals( 23, $validator->fixup( "23" ) );
        $this->assertEquals( -23, $validator->fixup( "-23" ) );
        $this->assertEquals( "foo", $validator->fixup( "foo" ) );
        $this->assertEquals( "23.42", $validator->fixup( "23.42" ) );
        $this->assertEquals( "-23.42", $validator->fixup( "-23.42" ) );
        $this->assertEquals( "true", $validator->fixup( "true" ) );
        $this->assertEquals( "false", $validator->fixup( "false" ) );
        $this->assertEquals( 1, $validator->fixup( "1" ) );
        $this->assertEquals( 0, $validator->fixup( "0" ) );
        $this->assertEquals( "", $validator->fixup( "" ) );

        $validator->type = ezcConsoleQuestionDialogTypeValidator::TYPE_FLOAT;
        
        $this->assertEquals( (float) 23, $validator->fixup( "23" ) );
        $this->assertEquals( (float) -23, $validator->fixup( "-23" ) );
        $this->assertEquals( "foo", $validator->fixup( "foo" ) );
        $this->assertEquals( 23.42, $validator->fixup( "23.42" ) );
        $this->assertEquals( -23.42, $validator->fixup( "-23.42" ) );
        $this->assertEquals( 7E-10, $validator->fixup( "7E-10" ) );
        $this->assertEquals( 7e-10, $validator->fixup( "7e-10" ) );
        $this->assertEquals( 4E3, $validator->fixup( "4E3" ) );
        $this->assertEquals( "true", $validator->fixup( "true" ) );
        $this->assertEquals( "false", $validator->fixup( "false" ) );
        $this->assertEquals( 1, $validator->fixup( "1" ) );
        $this->assertEquals( 0, $validator->fixup( "0" ) );
        $this->assertEquals( "", $validator->fixup( "" ) );

        $validator->type = ezcConsoleQuestionDialogTypeValidator::TYPE_BOOL;
        
        $this->assertEquals( "23", $validator->fixup( "23" ) );
        $this->assertEquals( "-23", $validator->fixup( "-23" ) );
        $this->assertEquals( "foo", $validator->fixup( "foo" ) );
        $this->assertEquals( 23.42, $validator->fixup( "23.42" ) );
        $this->assertEquals( -23.42, $validator->fixup( "-23.42" ) );
        $this->assertEquals( true, $validator->fixup( "true" ) );
        $this->assertEquals( false, $validator->fixup( "false" ) );
        $this->assertEquals( true, $validator->fixup( "1" ) );
        $this->assertEquals( false, $validator->fixup( "0" ) );
        $this->assertEquals( "", $validator->fixup( "" ) );

        $validator->default = "foo";

        $this->assertEquals( "foo", $validator->fixup( "" ) );
    }

    public function testGetResultString()
    {
        $validator = new ezcConsoleQuestionDialogTypeValidator();

        $this->assertEquals( "(<string>)", $validator->getResultString() );

        $validator->default = "foo";

        $this->assertEquals( "(<string>) [foo]", $validator->getResultString() );

        $validator->type = ezcConsoleQuestionDialogTypeValidator::TYPE_INT;
        $validator->default = null;

        $this->assertEquals( "(<int>)", $validator->getResultString() );

        $validator->default = 23;
        
        $this->assertEquals( "(<int>) [23]", $validator->getResultString() );
        
        $validator->type = ezcConsoleQuestionDialogTypeValidator::TYPE_FLOAT;
        $validator->default = null;

        $this->assertEquals( "(<float>)", $validator->getResultString() );

        $validator->default = 23.42;
        
        $this->assertEquals( "(<float>) [23.42]", $validator->getResultString() );
        
        $validator->type = ezcConsoleQuestionDialogTypeValidator::TYPE_BOOL;
        $validator->default = null;

        $this->assertEquals( "(<bool>)", $validator->getResultString() );

        $validator->default = true;
        
        $this->assertEquals( "(<bool>) [1]", $validator->getResultString() );
    }
}

?>
