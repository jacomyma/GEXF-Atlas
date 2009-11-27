<?php
/**
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.3.1
 * @filesource
 * @package WorkflowDatabaseTiein
 * @ignore
 */
require_once '../../../trunk/Base/src/base.php';

function __autoload( $className )
{
    ezcBase::autoload( $className );
}

$db = ezcDbFactory::create( 'mysql://test@localhost/test' );

$schema = ezcDbSchema::createFromDb( $db );
$schema->writeToFile( 'array', '../tests/workflow.dba' );

file_put_contents(
  '../tests/workflow.dba',
  str_replace(
    '<?php return array (',
    '<?php
return array (',
    file_get_contents('../tests/workflow.dba')
  )
);
?>
