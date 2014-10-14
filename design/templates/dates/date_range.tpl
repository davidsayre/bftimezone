{* 
	@ts_from - timestamp
	@ts_to - timstamp
	@tz_offset - string (signed integer) +/-8
	@tz_label - string Eastern Standard Time

	Date range hides 12:00AM, collapses same start and end day 
	Shows AM/PM 

	diff year: May 25, 2014 - May 25, 2015
	diff year, diff month, diff day: May 25, 2014 - June 3, 2015 (same as diff year)	
	diff year, same month, diff day: May 25, 2014 - May 26, 2015 (same as diff year)
	Same year, diff month, same day: May 25, 2014 - June 25, 2014 (same as diff year)	
	Same year, same month, diff day: May 25, 2014 - May 26, 2014 (same as diff year)
	Same year, same month, same day, diff time: May 24, 2014 9:00 PM - 10:00 PM EST
	Same year, same month, diff day, same time: May 25, 2014 9:00 PM EST - May 26, 2014 9:00 PM EST
	diff year, same month, diff day, diff time: May 25, 2014 9:00 PM EST - May 26, 2015 7:00 PM EST
*} 
{def $sDateFrom = '' $sDateTo = '' $bShowTime = true() $sTzLabel = false() $sTZOffset = false()}
{if is_set($show_time)} {set $bShowTime = $show_time} {/if}
{if is_set($tz_offset)} {set $sTZOffset = $tz_offset} {/if}
{if is_set($tz_label)} {set $sTzLabel = $tz_label} {/if}

{date_range_tz($ts_from, $ts_to, $sTZOffset, $sTzLabel, $bShowTime)}