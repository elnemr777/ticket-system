<?php
                                       // إعدادات Discord
$client_id    = '1508459067998474414'; // 👈 حط الـ Client ID هنا
$redirect_uri = 'http://localhost/backend/ticket-system/auth/discord-callback.php';

// تحقق من وجود Client ID
if (empty($client_id) || $client_id == '1234567890') {
    die('❌ Error: Client ID مش موجود! روح Discord Developers وجيب الـ Client ID');
}

// بناء رابط Discord OAuth
$discord_url = "https://discord.com/oauth2/authorize?" . http_build_query([
    'client_id'     => $client_id,
    'redirect_uri'  => $redirect_uri,
    'response_type' => 'code',
    'scope'         => 'identify email',
]);

// عرض الرابط للتأكد (للتجربة فقط)
echo "Discord Login URL: <br>";
echo "<a href='$discord_url'>$discord_url</a><br><br>";
echo "جاري التوجيه...";

// توجيه المستخدم لـ Discord
header("Location: " . $discord_url);
exit();
