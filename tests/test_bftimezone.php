<?php 

require "autoload.php";

/*
	Test the bfTimezone class	
*/

$aTestDates = array(
	array("diff-year same-month same-day","May 25, 2014 - May 25, 2015")
	,array("diff-year diff-month diff-day","May 25, 2014 - June 3, 2015")
	,array("diff-year same-month diff-day","May 25, 2014 - May 26, 2015")
	,array("same-year diff-month same-day","May 25 2014 - June 25, 2014")	
	,array("same-year same-month diff-day","May 25 2014 - May 26, 2014")
	,array("same-year same-month same-day diff-time","May 24, 2014 9:00 PM - May 24, 2014 10:00 PM EST")
	,array("same-year same-month diff-day same-time diff timezone","May 25, 2014 9:00 PM EST - May 26, 2014 9:00 PM PST")
	,array("diff year same-month diff-day diff-time same-timezone","May 25, 2014 9:00 PM EST - May 26, 2015 7:00 PM EST")
);
foreach($aTestDates as $aTest) {
	$sTestId = $aTest[0];
	$sTestRange = $aTest[1];
	$aRange = explode(' - ', $sTestRange);
	$dRangeStart = strtotime($aRange[0]);
	$dRangeStop = strtotime($aRange[1]);
	echo 'Test ' . $sTestId. " [" .$sTestRange. "] -> ".bfTimezone::dateRangeTimezone($dRangeStart,$dRangeStop,0,0,true)."\n";
}

print_r(bfTimezone::getTimeZone(mktime(5,30,0,9,5,2014), "33.396524", "104.959602"));
exit;
?>