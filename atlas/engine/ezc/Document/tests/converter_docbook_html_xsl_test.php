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
class ezcDocumentConverterDocbookToHtmlXsltTests extends ezcTestCase
{
    protected static $testDocuments = null;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( __CLASS__ );
    }

    public static function getTestDocuments()
    {
        if ( self::$testDocuments === null )
        {
            // Get a list of all test files from the respektive folder
            $testFiles = glob( dirname( __FILE__ ) . '/files/docbook/xhtml_xslt/s_*.xml' );

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
        return array_slice( self::$testDocuments, -1, 1 );
    }

    public function testExtensionMissingException()
    {
        if ( ezcBaseFeatures::hasExtensionSupport( 'xsl' ) )
        {
            $this->markTestSkipped( 'You need XSLT support disabled for this test.' );
        }

        try
        {
            $converter = new ezcDocumentDocbookToHtmlXsltConverter();
            $this->fail( 'Expected ezcBaseExtensionNotFoundException.' );
        }
        catch ( ezcBaseExtensionNotFoundException $e )
        { /* Expected */ }
    }

    public function testConversionFailure()
    {
        if ( !ezcBaseFeatures::hasExtensionSupport( 'xsl' ) )
        {
            $this->markTestSkipped( 'You need XSLT support for this test.' );
        }

        try
        {
            $doc = new ezcDocumentDocbook();
            $doc->loadFile( dirname( __FILE__ ) . '/files/docbook/xhtml_xslt/s_001_empty.xml' );

            $converter = new ezcDocumentDocbookToHtmlXsltConverter();
            $converter->options->failOnError = true;
            $converter->convert( $doc );

            $this->fail( 'Expected ezcDocumentErroneousXmlException.' );
        }
        catch ( ezcDocumentErroneousXmlException $e )
        {
            $this->assertTrue( 
                count( $e->getXmlErrors() ) > 0,
                'Expected some conversion errors / notices.'
            );
        }
    }

    /**
     * @dataProvider getTestDocuments
     */
    public function testLoadXmlDocumentFromFile( $from, $to )
    {
        if ( !ezcBaseFeatures::hasExtensionSupport( 'xsl' ) )
        {
            $this->markTestSkipped( 'You need XSLT support for this test.' );
        }

        if ( !is_file( $to ) )
        {
            $this->markTestSkipped( "Comparision file '$to' not yet defined." );
        }

        $doc = new ezcDocumentDocbook();
        $doc->loadFile( $from );

        $converter = new ezcDocumentDocbookToHtmlXsltConverter();
        $created = $converter->convert( $doc );

        $this->assertTrue(
            $created instanceof ezcDocumentXhtml
        );

        // Replace creator string in generated document, as this may change too
        // often for proper testing.
        $dom = $created->getDomDocument();
        $domContent = $dom->saveXml();

        // Just test that some kind of HTML has been created - everything is up
        // to the used XSL and may change any time.
        $this->assertTrue(
            strpos( $domContent, '<html' ) !== false
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }
}

?>
