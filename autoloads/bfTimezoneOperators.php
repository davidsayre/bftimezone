<?php

/**
 * Author: Curtis.Holland @ beaconfire .com
 * Author: David.Sayre @ beaconfire .com
 */
class bfTimezoneOperators{
	/**
	 * Return an array with the template operator name.
	 *
	 * @return array
	 */
	function operatorList()
	{
		return array( 'date_range_tz', 'date_tz');
	}

	/**
	 * Return true to tell the template engine that the parameter list exists per operator type,
	 * this is needed for operator classes that have multiple operators.
	 *
	 * @return bool
	 */
	function namedParameterPerOperator(){return true;}

	/**
	 * Returns an array of named parameters, this allows for easier retrieval
	 * of operator parameters. This also requires the function modify() has an extra
	 * parameter called $namedParameters.
	 *
	 * @return array
	 */
	function namedParameterList(){

		return array(
			'date_tz' => array(
				'strZoneCorrection' => array(
						'type'        => 'string',
						'required'    => true,
						'default'     => false
					)
			),
			'date_range_tz' => array(
				'intTimestampStart' => array(
						'type'        => 'integer',
						'required'    => true,
						'default'     => false
					),
				'intTimestampEnd' => array(
						'type'        => 'integer',
						'required'    => true,
						'default'     => false
					),
				'strTimezoneOffset' => array(
						'type'        => 'string', //this is a string with signed (-/+) preffix
						'required'    => false,
						'default'     => false
					),
				'strTimezoneCode' => array(
						'type'        => 'string',
						'required'    => false,
						'default'     => false
					),
				'bShowTime' => array(
						'type'        => 'boolean',
						'required'    => false,
						'default'     => false
					)
			),
		);

	}

	/**
	 * Executes the PHP function for the operator cleanup and modifies $operatorValue.
	 *
	 * @param eZTemplate $tpl
	 * @param string $operatorName
	 * @param array $operatorParameters
	 * @param string $rootNamespace
	 * @param string $currentNamespace
	 * @param mixed $operatorValue
	 * @param array $namedParameters
	 */
	function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters ){
		switch ( $operatorName ){
			case 'date_tz':
				if($namedParameters['strZoneCorrection'] != "" && $operatorValue != ""){
					$operatorValue = bfTimezone::translateDate($operatorValue, $namedParameters['strZoneCorrection']);
				}
				break;
			case 'date_range_tz':
				$intStart = $namedParameters['intTimestampStart'];
				$intEnd = $namedParameters['intTimestampEnd'];
				$strOffset = $namedParameters['strTimezoneOffset'];
				$strTimezoneCode = $namedParameters['strTimezoneCode'];
				$bShowTime = $namedParameters['bShowTime'];
				
				$operatorValue = bfTimezone::dateRangeTimezone($intStart, $intEnd, $strOffset, $strTimezoneCode, $bShowTime);	
				break;
			default:
				break;
		}
	}

	
}

?>
