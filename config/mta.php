<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "roleplay";

$mta_conn = mysqli_connect($host, $username, $password, $database);

if (! $mta_conn) {
    die("DB Connection Failed");
}
