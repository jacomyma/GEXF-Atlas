<?php
/**
 * ezcDocumentConverterEzp3TpEzp4Tests
 * 
 * @package Document
 * @version 1.1.2
 * @subpackage Tests
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
class ezcDocumentConverterDocbookToHtmlTests extends ezcTestCase
{
    protected static $testDocuments = null;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( __CLASS__ );
    }

    public function testHtmlConverterOptionsFormatOutput()
    {
        $options = new ezcDocumentHtmlConverterOptions();
        $options->formatOutput = false;

        try
        {
            $options->formatOutput = 0;
            $this->fail( 'Expected ezcBaseValueException.' );
        }
        catch ( ezcBaseValueException $e )
        { /* Expected */ }
    }

    public function testHtmlConverterOptionsDublinCoreMetadata()
    {
        $options = new ezcDocumentHtmlConverterOptions();
        $options->dublinCoreMetadata = false;

        try
        {
            $options->dublinCoreMetadata = 0;
            $this->fail( 'Expected ezcBaseValueException.' );
        }
        catch ( ezcBaseValueException $e )
        { /* Expected */ }
    }

    public function testHtmlConverterOptionsStyleSheets()
    {
        $options = new ezcDocumentHtmlConverterOptions();
        $options->styleSheets = array( 'url' );
        $options->styleSheets = null;

        try
        {
            $options->styleSheets = 0;
            $this->fail( 'Expected ezcBaseValueException.' );
        }
        catch ( ezcBaseValueException $e )
        { /* Expected */ }
    }

    public function testHtmlConverterOptionsUnknownOption()
    {
        $options = new ezcDocumentHtmlConverterOptions();

        try
        {
            $options->notExistingOption = 0;
            $this->fail( 'Expected ezcBasePropertyNotFoundException.' );
        }
        catch ( ezcBasePropertyNotFoundException $e )
        { /* Expected */ }
    }

    public static function getTestDocuments()
    {
        if ( self::$testDocuments === null )
        {
            // Get a list of all test files from the respektive folder
            $testFiles = glob( dirname( __FILE__ ) . '/files/docbook/xhtml/s_*.xml' );

            // Create array with the test file and the expected result file
            foreach ( $testFiles as $file )
            {
                self::$testDocuments[] = array(
                    $file,
                    substr( $file, 0, -3 ) . 'html'
                );
            }
        }

        return self::$testDocuments;
        return array_slice( self::$testDocuments, 0, 3 );
    }

    /**
     * @dataProvider getTestDocuments
     */
    public function testLoadXmlDocumentFromFile( $from, $to )
    {
        if ( !is_file( $to ) )
        {
            $this->markTestSkipped( "Comparision file '$to' not yet defined." );
        }

        $doc = new ezcDocumentDocbook();
        $doc->loadFile( $from );

        $converter = new ezcDocumentDocbookToHtmlConverter();
        $converter->options->formatOutput = true;
        $created = $converter->convert( $doc );

        $this->assertTrue(
            $created instanceof ezcDocumentXhtml
        );

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'docbook_html_custom_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), $xml = $created->save() );

        $this->assertTrue(
            ( $errors = $created->validateString( $xml ) ) === true,
            ( is_array( $errors ) ? implode( PHP_EOL, $errors ) : 'Expected true' )
        );

        $this->assertEquals(
            file_get_contents( $to ),
            $xml
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }

    public function testDublinCoreMetadata()
    {
        $from = dirname( __FILE__ ) . '/files/docbook/xhtml/s_021_field_list.xml';
        $to   = dirname( __FILE__ ) . '/files/docbook/xhtml/s_021_field_list_dc.html';

        $doc = new ezcDocumentDocbook();
        $doc->loadFile( $from );

        $converter = new ezcDocumentDocbookToHtmlConverter();
        $converter->options->formatOutput       = true;
        $converter->options->dublinCoreMetadata = true;
        $created = $converter->convert( $doc );

        $this->assertTrue(
            $created instanceof ezcDocumentXhtml
        );

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'docbook_html_custom_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), $xml = $created->save() );

        $this->assertTrue(
            ( $errors = $created->validateString( $xml ) ) === true,
            ( is_array( $errors ) ? implode( PHP_EOL, $errors ) : 'Expected true' )
        );

        $this->assertEquals(
            file_get_contents( $to ),
            $xml
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }

    public function testWithStylesheets()
    {
        $from = dirname( __FILE__ ) . '/files/docbook/xhtml/s_021_field_list.xml';
        $to   = dirname( __FILE__ ) . '/files/docbook/xhtml/s_021_field_list_stylesheets.html';

        $doc = new ezcDocumentDocbook();
        $doc->loadFile( $from );

        $converter = new ezcDocumentDocbookToHtmlConverter();
        $converter->options->formatOutput       = true;
        $converter->options->dublinCoreMetadata = true;
        $converter->options->styleSheets = array( 
            'foo.css',
            'http://example.org/bar.css',
        );
        $created = $converter->convert( $doc );

        $this->assertTrue(
            $created instanceof ezcDocumentXhtml
        );

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'docbook_html_custom_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), $xml = $created->save() );

        $this->assertEquals(
            file_get_contents( $to ),
            $xml
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }
}

?>
