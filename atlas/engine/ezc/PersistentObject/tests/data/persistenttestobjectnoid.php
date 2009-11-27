<?php
/*
 * Holds the definition for PersistentTestObject
 * This definition is used by the code manager for
 * various tests in the system.
 */
// build definition
$def = new ezcPersistentObjectDefinition();
$def->table = "PO_test";
$def->class = "PersistentTestObjectNoId";

$def->properties['varchar'] = new ezcPersistentObjectProperty;
$def->properties['varchar']->columnName = 'type_varchar';
$def->properties['varchar']->propertyName = 'varchar';
$def->properties['varchar']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->properties['integer'] = new ezcPersistentObjectProperty;
$def->properties['integer']->columnName = 'type_integer';
$def->properties['integer']->propertyName = 'integer';
$def->properties['integer']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->properties['decimal'] = new ezcPersistentObjectProperty;
$def->properties['decimal']->columnName = 'type_decimal';
$def->properties['decimal']->propertyName = 'decimal';
$def->properties['decimal']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_FLOAT;;

$def->properties['text'] = new ezcPersistentObjectProperty;
$def->properties['text']->columnName = 'type_text';
$def->properties['text']->propertyName = 'text';
$def->properties['text']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;;
return $def;

?>
