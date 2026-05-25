<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "ticket-system";

$site_conn = mysqli_connect($host, $username, $password, $database);

if (! $site_conn) {
    die("DB Connection Failed");
}
