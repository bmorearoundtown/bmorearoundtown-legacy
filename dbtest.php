<?php
$link = mysql_connect('bmorearoundtown.ipowermysql.com', 'newuser_2155', '0151000b');
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';
mysql_select_db("bmorearoundtown") or die("Could not open the db");
mysql_close($link);
?>

  