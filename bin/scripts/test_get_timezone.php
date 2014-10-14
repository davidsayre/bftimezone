<?php 

require "autoload.php";
print_r(bfTimezone::getTimeZone(mktime(5,30,0,9,5,2014), "33.396524", "104.959602"));
exit;
?>