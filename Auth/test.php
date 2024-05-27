<?php

$serverName = "localhost";
$port = 80;
$usrName = "rahul";
$passwd = "..//rahul";
$dbName = "Project";

$c = mysqli_connect($serverName, $usrName, $passwd, $dbName, $port);
if (! $c) {
    die("Connection Failed");
}
else {
    echo "Connection Successful";
}



?>
