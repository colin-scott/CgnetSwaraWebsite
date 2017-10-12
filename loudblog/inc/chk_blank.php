<?php
function chk_blank($file)
{
	$s1 = "python test.py ".$file;
	$get = exec($s1); 
	return $get;
}
?>

