<html><head><title>Recent Calls</title></head><body>
<?php
$db_host = '127.0.0.1';
$db_user = 'root';
$db_pwd = 'Wmtp00lr!';

$database = 'audiwikiswara';

if (!mysql_connect($db_host, $db_user, $db_pwd))
    die("Can't connect to database");

if (!mysql_select_db($database))
    die("Can't select database");

// sending query
$result = mysql_query("SELECT calldate, src, dst, dcontext, channel, disposition FROM cdr WHERE dcontext='callback' or dcontext='PRI' ORDER BY calldate desc LIMIT 500");
if (!$result) {
    die("Query to show fields from table failed");
}

$fields_num = mysql_num_fields($result);

echo "<h1>Recent calls (<a href=\"call-log-csv.php\">Download CSV</a>)</h1>";
echo "<b>Note</b>:  where dst=4099222 it is an incoming missed call to the PRI number.<br><br>";
echo "<table border='1'><tr>";
// printing table headers
for($i=0; $i<$fields_num; $i++)
{
    $field = mysql_fetch_field($result);
    echo "<td>{$field->name}</td>";
}
echo "</tr>\n";
// printing table rows
while($row = mysql_fetch_row($result))
{
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
        echo "<td>$cell</td>";

    echo "</tr>\n";
}
echo "</table>";
mysql_free_result($result);
?>
</body></html>
