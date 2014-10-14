bftimezone extension
=================

Provides enhanced timezone functionality for eZ Publish.


Installation
------------

bftimezone is an eZ extension. Just drop it into your extensions and enable it.

Usage
-----

### date_range_tz operator

Provides nicely formatted date ranges with d,m,Y collapsing
Will not render a time of '00:00' (unless timezone offset provided). Prevents 12:00 AM from being displayed

**Usage:** `{$date_range_tz($param,$param,..)}`

### date_tz operator

Returns all a formatted date string with timezone information

**Example:** `{date_tz( )}`


Add/edit Class fields
---------------------

* geo_location [gmaplocation] field - "All times must be Eastern Standard Time! If an event is in Sanfrancisco, CA at 9pm then it's 00:00 EST. "
* timezone_name [text, no search] - "Timezone label (Eastern Daylight Savings Time) as reported by Google Timezone API; blank to recalculate from geo location field"
* timezone_offset [text, no search] - " +/- from GMT ; blank to recalculate from geo location field "


Workflow Event
---------------

Create 'Calculate Timezone After Publish' workflow
Add/Edit the 'Content Publish After Multiplexer' to route desired class(es) to 'Calculate Timezone After Publish'
Add 'Event / Calculate Timezone' to 'Calculate Timezone After Publish'

Event Business Rules
--------------------

If timezone_name and timezone_offset are empty
	The object's geo_location is used to query google timezone API for entered time + location
	This gives the offset that will be at that location + that time. This accounts for daylight savings.
	The empty timezone_name and timezone_offset fields are populated
If the timezone_name and timezone_offset fields are populated then nothing is updated.
	This prevents manually set values from being overwritten
	This allows editors to 'clear' out the fields and send for publish again to re-calcuate the timezone

Editor Date Input Rules
-----------------------

v1 - ALL TIME IS ENTERED Eastern Standard Time!
This puts all the objects on the same timeline and timezone
This can be difficult to calculate and an enhanced date picker is planned to help in EST vs local time selection

Wishlist
--------

v2 will use a timezone aware date picker to calculate the local time -> server time -> date field conversion
