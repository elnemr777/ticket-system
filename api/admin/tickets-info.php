<?php

session_start();
header("Content-Type: application/json");

if ($_SESSION['role'] !== 'admin') {
    echo json_encode([
        'success' => false]);
    exit();
}

include __DIR__ . "/../../config/site.php";

//total tickets
$totalT = mysqli_fetch_assoc(mysqli_query($site_conn,
    "SELECT COUNT(*) as count FROM tickets"))['count'];

//open tickets
$openT_query = mysqli_fetch_assoc(mysqli_query($site_conn,
    "SELECT COUNT(*) as count FROM tickets WHERE status='open'"))['count'];

//progress tickets
$progressT_query = mysqli_fetch_assoc(mysqli_query($site_conn,
    "SELECT COUNT(*) as count FROM tickets WHERE status='progress'"))['count'];

//closed tickets
$closedT_query = mysqli_fetch_assoc(mysqli_query($site_conn,
    "SELECT COUNT(*) as count FROM tickets WHERE status='closed'"))['count'];

$resolvedT = mysqli_fetch_assoc(mysqli_query($site_conn,
    "SELECT COUNT(*) as count FROM tickets WHERE status='resolved'"))['count'];

$filter = $_GET['filter'] ?? 'all';

$sql = "SELECT
tickets.id,
tickets.title,
tickets.user_id,
tickets.category,
tickets.priority,
tickets.status,
tickets.assigned_admin_id,
tickets.created_at,
tickets.updated_at
FROM tickets ";

if ($filter === 'unassigned') {
    $sql = $sql . " WHERE tickets.assigned_admin_id IS NULL";
} elseif ($filter !== 'all') {
    $filter = mysqli_real_escape_string($site_conn, $filter);
    $sql    = $sql . " WHERE tickets.status = '$filter'";
}

$sql           = $sql . " ORDER BY tickets.created_at DESC";
$tickets_query = mysqli_query($site_conn, $sql);

$tickets = [];

while ($row = mysqli_fetch_assoc($tickets_query)) {
    $player_query = mysqli_query($site_conn,
        "SELECT ingame_username FROM users WHERE id = '" . $row['user_id'] . "' LIMIT 1"
    );

    $player             = mysqli_fetch_assoc($player_query);
    $row['player_name'] = $player['ingame_username'];

    if ($row['assigned_admin_id'] !== null) {
        $admin_query = mysqli_query($site_conn,
            "SELECT ingame_username FROM users WHERE id = '" . $row['assigned_admin_id'] . "' LIMIT 1"
        );
        $admin             = mysqli_fetch_assoc($admin_query);
        $row['admin_name'] = $admin['ingame_username'];
    } else {
        $row['admin_name'] = null;
    }

    $tickets[] = $row;
}

echo json_encode([
    'success'    => true,
    'tickets'    => $tickets,
    'admin_name' => $_SESSION['username'],
    'stats'      => [
        'total'    => $totalT,
        'open'     => $openT_query,
        'progress' => $progressT_query,
        'resolved' => $resolvedT,
        'closed'   => $closedT_query,
    ],
]);
