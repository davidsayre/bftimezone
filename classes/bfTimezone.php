<?php

class bfTimezone {
	
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
		$intTransfomredDate = $intUTCDate + ($strZoneCorrection*60*60);
		return $intTransfomredDate;
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
		$strDateFrom = "";
		$strDateTo = "";

		$strDateFrom .= date("F d", $intTZStart);		
		if(date("Y",$intTZStart) != date("Y",$intTZEnd)){
			$strDateFrom .= date(", Y", $intTZStart);
			$strDateTo .= $strDateTo." ".date("F d, Y", $intTZEnd); /* different year */
		}else{
			/* same year */
			if(date("FY",$intTZStart) != date("FY",$intTZEnd)){
				$strDateTo = date("F d", $intTZEnd); /* different month year */
				$strDateFrom .= date(", Y", $intTZStart);
			}else{
				/* same month, year */
				if(date("FdY",$intTZStart) != date("FdY",$intTZEnd)){
					$strDateTo = date("F d, Y", $intTZEnd); /* diferent month, day, year */
				}else{
					$strDateFrom .= date(", Y", $intTZStart);
					/* same day */
					if($bShowTime) {
						$sTimeFrom = '';
						$sTimeTo = '';
						if(empty($strOffset)) {
							//offset is not found, check for midnight trick					
							if( date("Hi", $intStart) != "0000" ) {
								$sTimeFrom = date("g:i A", $intTZStart); /* append not 00:00 time */
							}
							if( date("Hi", $intTZEnd) != "0000" ) {	
								$sTimeTo = date("g:i A", $intTZEnd); /* append not 00:00 time */
							}
						} else {
							//offset found allow time append requested
							$sTimeFrom = date("g:i A", $intTZStart); /* append not 00:00 time */
							$sTimeTo = date("g:i A", $intTZEnd); /* append not 00:00 time */
						}
						//check for same end time
						if( date("Hi", $intTZEnd) == date("Hi", $intStart) ) {
							$sTimeTo = '';
						}
						if(!empty($sTimeFrom)) { $strDateFrom .= " " .trim($sTimeFrom); } //append
						if(!empty($sTimeTo)) { $strDateTo .= " " .trim($sTimeTo); } //append
					}
				}
			}
		}

		$sTimezoneAbbr = self::abbeviateTimezone($strTimezoneCode);

		return $strDateFrom.($strDateTo!=""?" - ".trim($strDateTo):"").($bShowTime&&$strTimezoneCode!=""?" <span class='tz_code' title='".$strTimezoneCode."'>".$sTimezoneAbbr."</span>":"");
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