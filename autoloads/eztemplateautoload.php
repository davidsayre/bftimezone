<?php

/**
 * Look in the operator files for documentation on use and parameters definition.
 *
 * @var array $eZTemplateOperatorArray
 */
$eZTemplateOperatorArray = array();

$eZTemplateOperatorArray[] = array( 
	'script'         => 'extension/bftimezone/autoloads/bfTimezoneOperators.php',
	'class'          => 'bfTimezoneOperators',
	'operator_names' => array( 'date_tz', 'date_range_tz')
);

?>