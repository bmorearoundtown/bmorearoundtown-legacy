<?php
$link = mysql_connect("bmorearoundtown.ipowermysql.com", "newuser_2222", "0151000b");
if (!$link) {
    die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully';
mysql_select_db(bmorearoundtown);
?>
