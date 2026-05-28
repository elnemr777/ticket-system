<?php

session_start();
header('Content-Type: application/json');

if (! isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false]);
    exit();
}

include __DIR__ . "/../../config/site.php";

$user_id = $_SESSION['user_id'];

//total tickets
$totalT_query = mysqli_query($site_conn,
    "SELECT COUNT(*) as count FROM tickets WHERE user_id='$user_id'"
);
$total_tickets = mysqli_fetch_assoc($totalT_query)['count'];

//open tickets
$openT_query = mysqli_query($site_conn,
    "SELECT COUNT(*) as count FROM tickets WHERE user_id='$user_id' AND status='open'"
);
$open_tickets = mysqli_fetch_assoc($openT_query)['count'];

//progress tickets
$progressT_query = mysqli_query($site_conn,
    "SELECT COUNT(*) as count FROM tickets WHERE user_id='$user_id' AND status='progress'"
);
$progress_tickets = mysqli_fetch_assoc($progressT_query)['count'];

//closed tickets
$closedT_query = mysqli_query($site_conn,
    "SELECT COUNT(*) as count FROM tickets WHERE user_id='$user_id' AND status='closed'"
);
$closed_tickets = mysqli_fetch_assoc($closedT_query)['count'];

// Filter
$filter = $_GET['filter'] ?? 'all';

$sql = "SELECT t.*, u.ingame_username AS admin_name
        FROM tickets t
        LEFT JOIN users u ON t.assigned_admin_id = u.id
        WHERE t.user_id = '$user_id'";

if ($filter !== 'all') {
    $filter  = mysqli_real_escape_string($site_conn, $filter);
    $sql    .= " AND t.status = '$filter'";
}

$sql .= " ORDER BY t.created_at DESC";

$tickets_query = mysqli_query($site_conn, $sql);

$tickets = [];
while ($row = mysqli_fetch_assoc($tickets_query)) {
    $tickets[] = $row;
}

echo json_encode([
    'success' => true,
    'tickets' => $tickets,
    'stats'   => [
        'total'    => $total_tickets,
        'open'     => $open_tickets,
        'progress' => $progress_tickets,
        'closed'   => $closed_tickets,
    ],
]);
