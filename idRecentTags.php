<?php

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
	$arr = Array();
	$db_tags_query = "SELECT tags FROM {$GLOBALS['prefix']}lb_postings WHERE posted > '2017-01-01 00:00:00'";
	$tags = $GLOBALS['lbdata']->GetArray($db_tags_query);
	//echo ($tags[0]['tags']);
	foreach ($tags as $tag){
		$tagList = explode(' ',$tag['tags']);
		$arr = array_merge($arr, $tagList);
		
	}
	
	$ardic = Array();
	foreach ($arr as $ar){
		if ($ar!=''){
		if (isset($ardic[$ar]))
			$ardic[$ar] += 1;
		else
			$ardic[$ar] = 1;
	}
	}
	arsort($ardic);
	$i = 0;
	print_r($ardic);
	foreach ($ardic as $a){
		echo key($a)."</br>";
		$i += 1;
		if ($i==3)
			break;
	}
?>
