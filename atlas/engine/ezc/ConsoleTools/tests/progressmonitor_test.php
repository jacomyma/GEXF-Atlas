<?php
/**
 * ezcConsoleProgressMonitorTest class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 * @version 1.5.1
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Test suite for ezcConsoleProgressMonitor class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 */
class ezcConsoleProgressMonitorTest extends ezcTestCase
{
    private $stati = array(
        array( 'UPLOAD', '/var/upload/test.php' ),
        array( 'UPLOAD', '/var/upload/testing.php' ),
        array( 'UPLOAD', '/var/upload/foo.php' ),
        array( 'UPLOAD', '/var/upload/bar.php' ),
        array( 'UPLOAD', '/var/upload/baz.png' ),
        array( 'UPLOAD', '/var/upload/image.jpg' ),
        array( 'UPLOAD', '/var/upload/bar.gif' ),
        array( 'UPLOAD', '/var/upload/ez-logo.jpg' ),
        array( 'UPLOAD', '/var/upload/ez-logo.png' ),
        array( 'UPLOAD', '/var/upload/ez-components.png' ),
    );

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcConsoleProgressMonitorTest" );
	}

    public function testProgressMonitor1()
    {
        $out = new ezcConsoleOutput();
        $status = new ezcConsoleProgressMonitor( $out, 10 );
        ob_start();
        for ( $i = 0; $i < 10; $i++ )
        {
            $status->addEntry( $this->stati[$i][0], $this->stati[$i][1] );
        }
        $res = ob_get_contents();
        ob_end_clean();
        // To prepare test files use this:
        // file_put_contents( dirname( __FILE__ ) . '/data/' . ( ezcBaseFeatures::os() === "Windows" ? "windows/" : "posix/" ) . 'testProgressMonitor1.dat', $res );
        $this->assertEquals(
            file_get_contents( dirname( __FILE__ ) . '/data/' . ( ezcBaseFeatures::os() === "Windows" ? "windows/" : "posix/" ) . 'testProgressMonitor1.dat' ),
            $res,
            "Formated statusbar not generated correctly."
        );
    }
    
    public function testProgressMonitor2()
    {
        $out = new ezcConsoleOutput();
        $status = new ezcConsoleProgressMonitor( $out, 7 );
        ob_start();
        for ( $i = 0; $i < 7; $i++ )
        {
            $status->addEntry( $this->stati[$i][0], $this->stati[$i][1] );
        }
        $res = ob_get_contents();
        ob_end_clean();
        // To prepare test files use this:
        // file_put_contents( dirname( __FILE__ ) . '/data/' . ( ezcBaseFeatures::os() === "Windows" ? "windows/" : "posix/" ) . 'testProgressMonitor2.dat', $res );
        $this->assertEquals(
            file_get_contents( dirname( __FILE__ ) . '/data/' . ( ezcBaseFeatures::os() === "Windows" ? "windows/" : "posix/" ) . 'testProgressMonitor2.dat' ),
            $res,
            "Formated statusbar not generated correctly."
        );
    }
    
    public function testProgressMonitor3()
    {
        $out = new ezcConsoleOutput();
        $status = new ezcConsoleProgressMonitor( $out, 7, array( 'formatString' => '%2$10s %1$6.2f%% %3$s' ) );
        ob_start();
        for ( $i = 0; $i < 7; $i++ )
        {
            $status->addEntry( $this->stati[$i][0], $this->stati[$i][1] );
        }
        $res = ob_get_contents();
        ob_end_clean();
        // To prepare test files use this:
        // file_put_contents( dirname( __FILE__ ) . '/data/' . ( ezcBaseFeatures::os() === "Windows" ? "windows/" : "posix/" ) . 'testProgressMonitor3.dat', $res );
        $this->assertEquals(
            file_get_contents( dirname( __FILE__ ) . '/data/' . ( ezcBaseFeatures::os() === "Windows" ? "windows/" : "posix/" ) . 'testProgressMonitor3.dat' ),
            $res,
            "Formated statusbar not generated correctly."
        );
    }
    
    public function testProgressMonitor4()
    {
        $out = new ezcConsoleOutput();
        $out->formats->tag->color = 'red';
        $out->formats->percent->color = 'blue';
        $out->formats->percent->style = array( 'bold' );
        $out->formats->data->color = 'green';

        $status = new ezcConsoleProgressMonitor( $out, 7, 
            array( 'formatString' => 
                $out->formatText( '%2$10s', 'tag' ) . ' ' . $out->formatText( '%1$6.2f%%', 'percent' ) . ' ' . $out->formatText( '%3$s', 'data' ) ) );
        ob_start();
        for ( $i = 0; $i < 7; $i++ )
        {
            $status->addEntry( $this->stati[$i][0], $this->stati[$i][1] );
        }
        $res = ob_get_contents();
        ob_end_clean();
        // To prepare test files use this:
        // file_put_contents( dirname( __FILE__ ) . '/data/' . ( ezcBaseFeatures::os() === "Windows" ? "windows/" : "posix/" ) . 'testProgressMonitor4.dat', $res );
        $this->assertEquals(
            file_get_contents( dirname( __FILE__ ) . '/data/' . ( ezcBaseFeatures::os() === "Windows" ? "windows/" : "posix/" ) . 'testProgressMonitor4.dat' ),
            $res,
            "Formated statusbar not generated correctly."
        );
    }

    public function testGetAccessSuccess()
    {
        $out = new ezcConsoleOutput();
        $status = new ezcConsoleProgressMonitor( $out, 7 );

        $this->assertEquals( new ezcConsoleProgressMonitorOptions(), $status->options );
    }

    public function testGetAccessFailure()
    {
        $out = new ezcConsoleOutput();
        $status = new ezcConsoleProgressMonitor( $out, 7 );

        try
        {
            echo $status->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "ezcBasePropertyNotFoundException not thrown on get access of invalid property ezcConsoleProgressMonitor->foo." );
    }

    public function testSetAccessSuccess()
    {
        $out = new ezcConsoleOutput();
        $status = new ezcConsoleProgressMonitor( $out, 7 );
        $opt = new ezcConsoleProgressMonitorOptions();

        $status->options = $opt;
        $this->assertSame( $opt, $status->options, "Value not correctly set for property ezcConsoleProgressMonitor->options." );
    }

    public function testSetAccessFailure()
    {
        $out = new ezcConsoleOutput();
        $status = new ezcConsoleProgressMonitor( $out, 7 );

        $exceptionThrown = false;
        try
        {
            $status->options = 23;
        }
        catch ( ezcBaseValueException $e )
        {
            $exceptionThrown = true;
        }
        $this->assertTrue( $exceptionThrown, "ezcBaseValueException not thrown on invalid value for ezcConsoleProgressMonitor->options." );

        $exceptionThrown = false;
        try
        {
            $status->foo = 23;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $exceptionThrown = true;
        }
        $this->assertTrue( $exceptionThrown, "ezcBaseValueException not thrown on set access of invalid property ezcConsoleProgressMonitor->options." );
    }

    public function testIssetAccess()
    {
        $out = new ezcConsoleOutput();
        $status = new ezcConsoleProgressMonitor( $out, 7 );
        
        $this->assertTrue( isset( $status->options ) );
        $this->assertFalse( isset( $status->foo ) );
    }

    public function testSetOptionsSuccess()
    {
        $out = new ezcConsoleOutput();
        
        $optArr = array();
        $optArr["formatString"] = "Foo bar";
        $optObj = new ezcConsoleProgressMonitorOptions();
        $optObj->formatString = "Foo bar";

        $status = new ezcConsoleProgressMonitor( $out, 7 );
        $status->setOptions( $optArr );
        $this->assertEquals( $optObj, $status->options, "Options not set correctly from array." );
        
        $status = new ezcConsoleProgressMonitor( $out, 7 );
        $status->setOptions( $optObj );
        $this->assertSame( $optObj, $status->options, "Options not set correctly from object." );
    }

    public function testSetOptionsFailure()
    {
        $out = new ezcConsoleOutput();
        $status = new ezcConsoleProgressMonitor( $out, 7 );
        try
        {
            $status->setOptions( 23 );
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "ezcBaseValueException not thrown on invalid parameter for setOptions()." );
    }

    public function testGetOptions()
    {
        $out = new ezcConsoleOutput();
        $status = new ezcConsoleProgressMonitor( $out, 7 );
        
        $this->assertSame( $status->options, $status->getOptions() );
    }
}
?>
