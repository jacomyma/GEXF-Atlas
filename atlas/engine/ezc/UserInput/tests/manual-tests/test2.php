<?php
/**
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4
 * @filesource
 * @package UserInput
 * @subpackage Tests
 */
?>
<html>
<head><title>Test 2</title></head>
<body>
<?php
require_once '../../../Base/src/base.php';

function __autoload( $className )
{
    ezcBase::autoload( $className );
}

if ( count( $_POST ) )
{
?>
<table border="1" align="center">
    <tr><th>Expected</th><th>Result</th></tr>
    <tr>
        <td>
            <pre>
<?php
    try
    {
        throw new ezcInputFormVariableMissingException( 'test' );
    }
    catch ( ezcInputFormException $e )
    {
        echo $e->getCode(), ': ', $e->getMessage();
    }
?>
        </td>
        <td>
            <pre>
<?php
    $def = array( 'test' => new ezcInputFormDefinitionElement( ezcInputFormDefinitionElement::REQUIRED, 'float' ) );
    try
    {
        $form = new ezcInputForm( INPUT_POST, $def );
    }
    catch ( ezcInputFormException $e )
    {
        echo $e->getCode(), ': ', $e->getMessage();
    }
?>
        </td>
    </tr>
</table>
</body>
</html>
<?php
}
else
{
?>
<form method="post" align="center">

<input type="submit" name="submit" value="Go!"/>
</form>
</body>
</html>
<?php
}
?>
