<html><head><title>Latest Report</title></head><body>
<?php
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pwd = 'Wmtp00lr!';

$database = 'audiwikiswara';
$table = 'lb_postings';

if (!mysql_connect($db_host, $db_user, $db_pwd))
    die("Can't connect to database");

if (!mysql_select_db($database))
    die("Can't select database");

// sending query
$result = mysql_query("SELECT id FROM {$table} ORDER BY id DESC");
if (!$result) {
    die("Query to show fields from table failed");
}

$fields_num = mysql_num_fields($result);

$value=mysql_result($result,0);

echo "$value";

//$row = mysql_fetch_row($result)
//foreach($row as $cell)
//  echo "$cell";

mysql_free_result($result);
?>
</body></html>
