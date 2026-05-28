<?php

session_start();
header("Content-Type: application/json");

if (! isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false]);
    exit();
}

include __DIR__ . "/../../config/site.php";

$ticket_id = $_POST['ticket_id'];
$message   = $_POST['message'];
$user_id   = $_SESSION['user_id'];

$replies_query = mysqli_query($site_conn,
    "INSERT into ticket_replies (ticket_id, user_id, message, created_at)
    VALUES ('$ticket_id', '$user_id', '$message', NOW())
    "
);

if ($replies_query) {
    echo json_encode([
        'success' => true,
    ]);
} else {
    echo json_encode([
        'success' => false,
    ]);
}
