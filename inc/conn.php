<?php

$host = 'DB_HOST';
$db = 'SB_NAME';
$user = 'DB_USER';
$pass = 'DB_PASS';

// setup the connection
$conn = mysql_pconnect($host, $user, $pass) or trigger_error(mysql_error(), E_USER_ERROR); 

// select the database
mysql_select_db($db, $conn);

?>