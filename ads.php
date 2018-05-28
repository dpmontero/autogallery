<?php

$ads = array();

$ads[0] = "";

$ads[1] = "";


$ads[2] = "";

$ads[3] = "";




header('Content-type: text/javascript');
echo "var ads;\n";
echo "ads =\" ".$ads[ array_rand( $ads, 1) ]."\";\n";
echo "document.write(ads);"


?>
