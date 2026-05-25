<?php
sesion_start();
header('Content-Type: application/json');

if (! isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false]);
    exit();
}

include __DIR__ . "/../config/site.php";

$user_id = $_SESSION['user_id'];

// user data
$query = mysqli_query($site_conn, "SELECT * FROM users WHERE id='$user_id'");
$user  = mysqli_fetch_assoc($query);
// total tickets
$totalT_query = mysqli_query($site_conn,
    "SELECT COUNT(*) as count FROM tickets WHERE id='$user_id'");
$total_tickets = mysqli_fetch_assoc($totalT_query)['count'];
// total open tickets
$openT_query = mysqli_query($site_conn,
    "SELECT COUNT(*) as count FROM tickets WHERE user_id='$user_id' AND status IN ('open','progress') "
);
