<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.3
 * @filesource
 * @package EventLog
 * @subpackage Tests
 */

/**
 * @package EventLog
 * @subpackage Tests
 */
class ezcLogUnixFileWriterTest extends ezcTestCase
{
    protected $writer;

    protected function setUp()
    {
        $this->createTempDir("ezcLogTest_");
        $this->writer = new ezcLogUnixFileWriter( $this->getTempDir(), "default.log" );
    }

    protected function tearDown()
    {
        $this->removeTempDir();
    }

    public function testFileStructure()
    {
        $m = array("message" => "Alien alert.", "type" => 1, "source" => "UFO report", "category" => "fake warning" );
        $regExp = "/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d+ \d+:\d+:\d+ \[Debug\] \[UFO report\] \[fake warning\] Alien alert./";

        $this->assertRegExp($regExp, "Jan 24 09:32:26 [Debug] [UFO report] [fake warning] Alien alert.", "Testing myself");

        $this->writer->writeLogMessage( $m["message"], $m["type"], $m["source"], $m["category"] );
        $this->assertRegExp( $regExp, file_get_contents( $this->getTempDir() ."/default.log" ), 
                                "Content of default.log doesn't match with the regular expression.");
    }

    public function testExtraFields()
    {
        $m = array("message" => "Alien alert.", "type" => 1, "source" => "UFO report", "category" => "fake warning");
        $extra = array ("Line" => 42);
        $regExp = "/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d+ \d+:\d+:\d+ \[Debug\] \[UFO report\] \[fake warning\] Alien alert. \(Line: 42\)/";

        $this->writer->writeLogMessage( $m["message"], $m["type"], $m["source"], $m["category"], $extra );
        $this->assertRegExp( $regExp, file_get_contents( $this->getTempDir() ."/default.log" ), 
                                "Content of default.log doesn't match with the regular expression.");
    }

    public function testCategoryEmpty()
    {
        $m = array("message" => "Alien alert.", "type" => 1, "source" => "UFO report", "category" => false);
        $extra = array ("Line" => 42);
        $regExp = "/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) \d+ \d+:\d+:\d+ \[Debug\] \[UFO report\] Alien alert. \(Line: 42\)/";

        $this->writer->writeLogMessage( $m["message"], $m["type"], $m["source"], $m["category"], $extra );
        $this->assertRegExp( $regExp, file_get_contents( $this->getTempDir() ."/default.log" ), 
                                "Content of default.log doesn't match with the regular expression.");
    }

    public static function suite()
	{
		return new PHPUnit_Framework_TestSuite("ezcLogUnixFileWriterTest");
	}
}

?>
