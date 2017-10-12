<?php
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pwd = 'Wmtp00lr!';

$database = 'audiwikiswara';

if (!mysql_connect($db_host, $db_user, $db_pwd))
    die("Can't connect to database");

if (!mysql_select_db($database))
    die("Can't select database");

$result = mysql_query("SELECT calldate, src, dcontext, channel, disposition FROM cdr WHERE dcontext='callback' or dcontext='default' ORDER BY calldate desc LIMIT 500");
if (!$result) die('Couldn\'t fetch records');
$num_fields = mysql_num_fields($result);
$headers = array();
for ($i = 0; $i < $num_fields; $i++) {
  $headers[] = mysql_field_name($result , $i);
}
$fp = fopen('php://output', 'w');
if ($fp && $result) {
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="call-log.csv"');
  header('Pragma: no-cache');
  header('Expires: 0');
  fputcsv($fp, $headers);
  while ($row = mysql_fetch_array($result, MYSQLI_NUM)) {
    fputcsv($fp, array_values($row));
  }
  die;
}
?>