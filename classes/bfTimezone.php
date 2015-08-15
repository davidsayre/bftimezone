<?php

class bfTimezone {

	/* Requires bfcore extension and bfDateRange class */
	
	static $strURL = "https://maps.googleapis.com/maps/api/timezone/json?";

	static function getTimeZone($intEventTime, $flLat, $flLong){
		//currently, the intEventTime variable is in eastern time, we need to move it to UTC time
		$date = strtotime(DateTime::createFromFormat("U", $intEventTime)->format("Y-m-d H:i:s"));
		
		//now we need to pass the event date to the google api
		$objTimeZone = self::_callGoogleTimezoneApi(array('location' => $flLat.','.$flLong, 'timestamp' => $date));
		
		//let's add the human readable number
		$objTimeZone->humanOffset = "UTC ".(strpos($objTimeZone->rawOffset, "-")===false?"+":"").(($objTimeZone->rawOffset/60/60)+($objTimeZone->dstOffset/60/60));
		$objTimeZone->humanOffsetNoUTC = (strpos($objTimeZone->rawOffset, "-")===false?"+":"").(($objTimeZone->rawOffset/60/60)+($objTimeZone->dstOffset/60/60));
		return ($objTimeZone);
	}

	static function translateDate($intTimestamp, $strZoneCorrection){
		$intUTCDate = strtotime(DateTime::createFromFormat("U", $intTimestamp)->format("Y-m-d H:i:s"));
		$intTransformedDate = $intUTCDate + ($strZoneCorrection*60*60);
		return $intTransformedDate;
	}

	static function abbeviateTimezone($strTimezoneCode) {
		$sTimezoneAbbr = '';

		$aParts = explode(' ',$strTimezoneCode);
		foreach($aParts as $sWord) {
			$sTimezoneAbbr .= strtoupper(substr($sWord,0,1));
		}
		return $sTimezoneAbbr;
	}

	public static function dateRangeTimezone($intStart, $intEnd, $strOffset, $strTimezoneCode, $bShowTime){
		if($strOffset !== false && $strOffset != ""){			
			$intTZStart = bftimezone::translateDate($intStart, $strOffset);
			$intTZEnd = bftimezone::translateDate($intEnd, $strOffset);
		}else{
			$intTZStart = $intStart;
			$intTZEnd = $intEnd;
		}

		$bfDateRange = new bfDateRange(true);
		$sDateRange = $bfDateRange->getDateTimeRange($intTZStart,$intTZEnd,true,false,false,' ');

		//append TZ abbr
		if( ( stripos($sDateRange, 'am') !== false || stripos($sDateRange, 'pm') !== false) && $strTimezoneCode != "") {
			$sDateRange .= " <span class='tz_code' title='".$strTimezoneCode."'>".self::abbeviateTimezone($strTimezoneCode)."</span>";
		}

		return $sDateRange;
	}

	private function _callGoogleTimezoneApi($aryParams){
		$strURL = self::$strURL.http_build_query($aryParams);
		//echo $strURL."\n";
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $strURL); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
		$data = curl_exec($ch);
		return json_decode($data);
	}

}
?>