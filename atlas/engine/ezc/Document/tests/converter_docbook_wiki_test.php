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
class ezcDocumentConverterDocbookToWikiTests extends ezcTestCase
{
    protected static $testDocuments = null;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( __CLASS__ );
    }

    public function testCreateDocumentFromDocbook()
    {
        $doc = new ezcDocumentDocbook();
        $doc->loadFile( dirname( __FILE__ ) . '/files/docbook/wiki/s_001_empty.xml' );

        $wiki = new ezcDocumentWiki();
        $wiki->createFromDocbook( $doc );

        $this->assertSame(
            $wiki->save(),
            file_get_contents( dirname( __FILE__ ) . '/files/docbook/wiki/s_001_empty.txt' )
        );
    }

    public static function getTestDocuments()
    {
        if ( self::$testDocuments === null )
        {
            // Get a list of all test files from the respektive folder
            $testFiles = glob( dirname( __FILE__ ) . '/files/docbook/wiki/s_*.xml' );

            // Create array with the test file and the expected result file
            foreach ( $testFiles as $file )
            {
                self::$testDocuments[] = array(
                    $file,
                    substr( $file, 0, -3 ) . 'txt'
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

        $converter = new ezcDocumentDocbookToWikiConverter();
        $created = $converter->convert( $doc );

        $this->assertTrue(
            $created instanceof ezcDocumentWiki
        );

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'docbook_rst_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), $text = $created->save() );

        $this->assertTrue(
            ( $errors = $created->validateString( $text ) ) === true,
            ( is_array( $errors ) ? implode( PHP_EOL, $errors ) : 'Expected true' )
        );

        $this->assertEquals(
            file_get_contents( $to ),
            $text
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }
}

?>
