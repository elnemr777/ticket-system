<?php
session_start();

if (! isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include __DIR__ . '/../../config/site.php';

    $user_id     = $_SESSION['user_id'];
    $category    = mysqli_real_escape_string($site_conn, $_POST['category']);
    $title       = mysqli_real_escape_string($site_conn, $_POST['title']);
    $priority    = mysqli_real_escape_string($site_conn, $_POST['priority']);
    $description = mysqli_real_escape_string($site_conn, $_POST['description']);

    // insert ticket into db
    $tquery = mysqli_query($site_conn,
        "INSERT INTO tickets (user_id, title, category, priority, status, description, created_at, updated_at)
VALUES ('$user_id', '$title', '$category', '$priority', 'open', '$description', NOW(), NOW())"
    );

    if ($tquery) {

        $ticket_id = mysqli_insert_id($site_conn);

        if (! empty($_FILES['attachments']['name'][0])) {
            $upload_dir = __DIR__ . "/../../uploads/tickets/";

            for ($i = 0; $i < count($_FILES['attachments']['name']); $i++) {
                $file_name  = $_FILES['attachments']['name'][$i];
                $file_size  = $_FILES['attachments']['size'][$i];
                $file_tmp   = $_FILES['attachments']['tmp_name'][$i];
                $file_error = $_FILES['attachments']['error'][$i];

                if ($file_size > 10 * 1024 * 1024) {
                    echo "File too large";
                }

                if ($file_error !== 0) {
                    continue;
                }

                $new_name  = $ticket_id . "_" . time() . "_" . $i . "_" . $file_name;
                $file_path = $upload_dir . $new_name;

                if (move_uploaded_file($file_tmp, $file_path)) {
                    $db_path   = "uploads/tickets/" . "$new_name";
                    $safe_name = mysqli_real_escape_string($site_conn, $file_name);
                    mysqli_query($site_conn, "INSERT INTO ticket_attachments (ticket_id, file_name, file_path, uploaded_at)
                    VALUES ('$ticket_id', '$safe_name', '$db_path', NOW())
                    ");
                }

            }

        }

        header("Location: ../../pages/my-tickets.html");
        exit();

    } else {
        header("Location: ../../pages/new-ticket.html?error=failed");
        exit();
    }

}
