<?php
session_start();

// إعدادات Discord
$client_id     = '1508459067998474414';              // 👈 Client ID من Discord
$client_secret = 'bjHt8x9xIUNZqsmPeOT9v24d3mGAYVHB'; // 👈 Client Secret من Discord (مهم جداً!)
$redirect_uri  = 'http://localhost/backend/ticket-system/auth/discord-callback.php';

// التحقق من وجود الكود من Discord
if (! isset($_GET['code'])) {
    header('Location: ../login.php?error=discord_failed');
    exit();
}

$code = $_GET['code'];

// 1️⃣ تبديل الكود بـ Access Token
$token_url  = 'https://discord.com/api/oauth2/token';
$token_data = [
    'client_id'     => $client_id,
    'client_secret' => $client_secret,
    'grant_type'    => 'authorization_code',
    'code'          => $code,
    'redirect_uri'  => $redirect_uri,
];

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

$response = curl_exec($ch);
curl_close($ch);

$token_response = json_decode($response, true);

if (! isset($token_response['access_token'])) {
    header('Location: ../login.php?error=discord_token_failed');
    exit();
}

$access_token = $token_response['access_token'];

// 2️⃣ جلب بيانات المستخدم من Discord
$user_url = 'https://discord.com/api/users/@me';

$ch = curl_init($user_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $access_token,
]);

$user_response = curl_exec($ch);
curl_close($ch);

$discord_user = json_decode($user_response, true);

if (! isset($discord_user['id'])) {
    header('Location: ../login.php?error=discord_user_failed');
    exit();
}

// 3️⃣ بيانات المستخدم من Discord
$discord_id       = $discord_user['id'];
$discord_username = $discord_user['username'] . '#' . $discord_user['discriminator'];
$discord_avatar   = $discord_user['avatar'];

// 4️⃣ حفظ في Database
include __DIR__ . "/../config/site.php";

$check = mysqli_query($site_conn, "SELECT * FROM users WHERE discord_id='$discord_id' LIMIT 1");

if (mysqli_num_rows($check) == 0) {
    // مستخدم جديد - أضفه
    $insert = mysqli_query($site_conn, "INSERT INTO users (discord_id, discord_username, discord_avatar, role, created_at)
                                         VALUES ('$discord_id', '$discord_username', '$discord_avatar', 'player', NOW())");
    $user_id = mysqli_insert_id($site_conn);
    $role    = 'player';
} else {
    // مستخدم موجود - جيب بياناته
    $user_data = mysqli_fetch_assoc($check);
    $user_id   = $user_data['id'];
    $role      = $user_data['role'];
}

// 5️⃣ حفظ Session
$_SESSION['user_id']    = $user_id;
$_SESSION['username']   = $discord_username;
$_SESSION['discord_id'] = $discord_id;
$_SESSION['role']       = $role;
$_SESSION['logged_in']  = true;

// 6️⃣ التوجيه
if ($role == 'admin') {
    header("Location: ../pages/admin-dashboard.html");
} else {
    header("Location: ../pages/main.html");
}
exit();
