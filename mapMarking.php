<?php
header("Content-type: text/xml");

include "loudblog/custom/config.php";
include "loudblog/inc/database/adodb.inc.php";
include "loudblog/inc/connect.php";
include "loudblog/inc/functions.php";

//create some important globals
if (!isset($db['host'])) { die("<br /><br />Cannot find a valid configuration file! <a href=\"install.php\">Install Loudblog now!</a>"); }
$GLOBALS['prefix'] = $db['pref'];
$GLOBALS['path'] = $lb_path;
$GLOBALS['audiopath'] = $lb_path . "/audio/";
global $cat_table;


$xml=simplexml_load_file("mapLocs.xml");
foreach ($xml->marker as $mark) {
	$name = $mark['name'];
	//echo $name." ";
	$query_to_count = "SELECT COUNT( * ) FROM ".$GLOBALS['prefix']."lb_postings WHERE tags LIKE '%".$name."%'";
	$count = $GLOBALS['lbdata']->GetArray($query_to_count);	
	$total = $count[0]['COUNT( * )'] ;
	//echo $total."<br>";
	$mark -> addAttribute("noOfPosts", $total);

}

$out = $xml->asXML();
$out = implode("\n", array_filter(explode("\n", $out)));
echo $out;
?>