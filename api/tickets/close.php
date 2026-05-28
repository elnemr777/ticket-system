<?php
session_start();
header("Content-Type: application/json");

if (! isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode([
        'success' => false,
    ]);
    exit();
}

include __DIR__ . "/../../config/site.php";

$ticket_id = $_POST['ticket_id'];
$user_id   = $_SESSION['user_id'];

$close_query = mysqli_query($site_conn,
    "UPDATE tickets set status='closed' WHERE id='$ticket_id' AND user_id='$user_id'"
);

if ($close_query) {
    echo json_encode([
        'success' => true,
    ]);
} else {
    echo json_encode([
        'success' => false,
    ]);
}
