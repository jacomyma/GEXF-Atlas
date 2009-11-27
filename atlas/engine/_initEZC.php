<?php
require_once "__config.php";

set_include_path( $enginePath."\ezc".PATH_SEPARATOR.get_include_path() );
	
require_once "Base/src/base.php"; // dependent on installation method, see below
function __autoload( $className ){
	ezcBase::autoload( $className );
}
?>