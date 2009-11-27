<?php
require_once 'tutorial_autoload.php';

class customLazyUrlConfiguration implements ezcBaseConfigurationInitializer
{
    public static function configureObject( $urlCfg )
    {
        // set the basedir and script values
        $urlCfg->basedir = 'mydir';
        $urlCfg->script = 'index.php';

        // define delimiters for unordered parameter names
        $urlCfg->unorderedDelimiters = array( '(', ')' );

        // define ordered parameters
        $urlCfg->addOrderedParameter( 'section' );
        $urlCfg->addOrderedParameter( 'group' );
        $urlCfg->addOrderedParameter( 'category' );
        $urlCfg->addOrderedParameter( 'subcategory' );

        // define unordered parameters
        $urlCfg->addUnorderedParameter( 'game' );
    }
}

ezcBaseInit::setCallback( 
    'ezcUrlConfiguration', 
    'customLazyUrlConfiguration'
);

// Classes loaded and configured on first request
$url = new ezcUrl( 
    'http://www.example.com/mydir/index.php/groups/Games/Adventure/Adult/(game)/Larry/7',
    ezcUrlConfiguration::getInstance()
);
?>
