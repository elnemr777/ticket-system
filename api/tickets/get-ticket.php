<?php
session_start();
header("Content-Type: application/json");

if (! isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false]);
    exit();
}

include __DIR__ . "/../../config/site.php";

$ticket_id = $_GET['id'];

$ticket_query = mysqli_query($site_conn,
    "SELECT t.* , u.ingame_username AS admin_name
FROM tickets t LEFT JOIN users u ON t.assigned_admin_id = u.id
WHERE t.id = '$ticket_id'
"
);

$ticket = mysqli_fetch_assoc($ticket_query);

$chat_query = mysqli_query($site_conn,
    "SELECT r.*, u.ingame_username AS username, u.role
FROM ticket_replies r
JOIN users u ON r.user_id = u.id
WHERE r.ticket_id = '$ticket_id'
ORDER BY r.created_at ASC"
);

$replies = [];
while ($row = mysqli_fetch_assoc($chat_query)) {
    $replies[] = $row;
}

echo json_encode([
    'success' => true,
    'ticket'  => $ticket,
    'replies' => $replies,
]);
