<?php
 
class CalculateTimezoneType extends eZWorkflowEventType{
	const WORKFLOW_TYPE_STRING = 'calculatetimezone';
	
	function CalculateTimezoneType() {
		$this->eZWorkflowEventType( CalculateTimezoneType::WORKFLOW_TYPE_STRING, 'Calculate Timezones' );
		$this->setTriggerTypes( array( 'content' => array( 'publish' => array( 'after' ) ) ) );
	}
	
	function execute( $process, $event ) {
		$debug = eZDebug::instance();
		$parameters = $process->attribute( 'parameter_list' );
		eZLog::write( "Entering calculate timezone workflow" );

		$debug = eZDebug::instance();
		
		$objContentObject = eZContentObject::fetch($parameters['object_id']);
		$aryDataMap = $objContentObject->dataMap();

		//get geo-location
		$aryLatLong = array_filter(explode("|#", $aryDataMap['geo_location']->toString()));

		//get start time
		$intStartTime = $aryDataMap['from_time']->toString();

		$objTimezone = bfTimezone::getTimeZone($intStartTime, $aryLatLong[1], $aryLatLong[2]);
		$debug->writeNotice('Timezone response: '.print_r($objTimezone, true));

		if($objTimezone && $objTimezone->status == "OK"){
			$aryDataMap['timezone_offset']->fromString($objTimezone->humanOffsetNoUTC);
			$aryDataMap['timezone_offset']->store();
			$aryDataMap['timezone_name']->fromString($objTimezone->timeZoneName);
			$aryDataMap['timezone_name']->store();
		}

		return eZWorkflowType::STATUS_ACCEPTED;
	}
	
}

eZWorkflowEventType::registerEventType( CalculateTimezoneType::WORKFLOW_TYPE_STRING, 'CalculateTimezoneType' );

?>