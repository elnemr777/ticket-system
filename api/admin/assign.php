<?php

session_start();
header("Content-Type: application/json");

if (! isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([
        'success' => false,
    ]);
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo json_encode([
        'success' => false,
    ]);
    exit();
}

include __DIR__ . "/../../config/site.php";

$ticket_id = $_POST['ticket_id'];
$admin_id  = $_SESSION['user_id'];

$update = mysqli_query($site_conn,
    "UPDATE tickets SET assigned_admin_id = '$admin_id', status='progress' WHERE id='$ticket_id' "
);

if ($update) {
    echo json_encode([
        'success' => true,
    ]);
} else {
    echo json_encode([
        'success' => false,
    ]);
}
