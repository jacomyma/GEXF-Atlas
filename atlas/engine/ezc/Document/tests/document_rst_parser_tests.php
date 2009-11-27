<?php
/**
 * ezcDocumentRstParserTests
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
class ezcDocumentRstParserTests extends ezcTestCase
{
    protected static $testDocuments = null;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( __CLASS__ );
    }

    public function testParserOptionsXhtmlVisitor()
    {
        $options = new ezcDocumentRstOptions();
        $options->xhtmlVisitor = 'foo';

        try
        {
            $options->errorReporting = 0;
            $this->fail( 'Expected ezcBaseValueException.' );
        }
        catch ( ezcBaseValueException $e )
        { /* Expected */ }
    }

    public function testParserOptionsXhtmlVisitorOptions()
    {
        $options = new ezcDocumentRstOptions();
        $options->xhtmlVisitorOptions = new ezcDocumentHtmlConverterOptions();

        try
        {
            $options->errorReporting = 0;
            $this->fail( 'Expected ezcBaseValueException.' );
        }
        catch ( ezcBaseValueException $e )
        { /* Expected */ }
    }

    public function testParserOptionsUnknownOption()
    {
        $options = new ezcDocumentRstOptions();

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
            $testFiles = glob( dirname( __FILE__ ) . '/files/rst/parser/s_*.txt' );

            // Create array with the test file and the expected result file
            foreach ( $testFiles as $file )
            {
                self::$testDocuments[] = array(
                    $file,
                    substr( $file, 0, -3 ) . 'rst'
                );
            }
        }

        return self::$testDocuments;
        return array_slice( self::$testDocuments, -1, 1 );
    }

    /**
     * @dataProvider getTestDocuments
     */
    public function testParseRstFile( $from, $to )
    {
        if ( !is_file( $to ) )
        {
            $this->markTestSkipped( "Comparision file '$to' not yet defined." );
        }

        $tokenizer  = new ezcDocumentRstTokenizer();
        $parser     = new ezcDocumentRstParser();

        $document = $parser->parse( $tokenizer->tokenizeFile( $from ) );

        $this->assertTrue(
            $document instanceof ezcDocumentRstDocumentNode
        );

        $expected = include $to;

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'rst_parser_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), "<?php\n\nreturn " . var_export( $document, true ) . ";\n\n" );

        $this->assertEquals(
            $expected,
            $document,
            'Parsed document does not match expected document.',
            0, 20
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }

    public static function getErroneousTestDocuments()
    {
//        return array();
        return array(
            array(
                dirname( __FILE__ ) . '/files/rst/parser/e_001_non_aligned_text.txt',
                'Parse error: Fatal error: \'Unexpected indentation change from level 4 to 0.\' in line 4 at position 38.',
            ),
            array(
                dirname( __FILE__ ) . '/files/rst/parser/e_002_titles_mismatch.txt',
                'Parse error: Notice: \'Title underline length (12) is shorter then text length (13).\' in line 3 at position 1.',
            ),
            array(
                dirname( __FILE__ ) . '/files/rst/parser/e_003_titles_depth.txt',
                'Parse error: Fatal error: \'Title depth inconsitency.\' in line 13 at position 1.',
            ),
        );
    }

    /**
     * @dataProvider getErroneousTestDocuments
     */
    public function testParseErroneousRstFile( $file, $message )
    {
        $tokenizer  = new ezcDocumentRstTokenizer();
        $parser     = new ezcDocumentRstParser();

        try
        {
            $document = $parser->parse( $tokenizer->tokenizeFile( $file ) );
            $this->fail( 'Expected ezcDocumentRstParserException.' );
        }
        catch ( ezcDocumentParserException $e )
        {
            $this->assertSame(
                $message,
                $e->getMessage(),
                'Different parse error expected.'
            );
        }
    }
}

?>
