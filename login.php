<?php
    session_start();

    // معالجة تسجيل الدخول
    if (isset($_POST['server-login'])) {
    include __DIR__ . "/config/mta.php";
    include __DIR__ . "/config/site.php";

    $username = mysqli_real_escape_string($mta_conn, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($mta_conn, "SELECT * FROM accounts WHERE username='$username' LIMIT 1");

    if (mysqli_num_rows($query) > 0) {
        $user            = mysqli_fetch_assoc($query);
        $hashed_password = md5($password);

        if ($hashed_password === $user['password']) {
            $check = mysqli_query($site_conn, "SELECT * FROM users WHERE ingame_username='$username' LIMIT 1");

            if (mysqli_num_rows($check) == 0) {
                mysqli_query($site_conn, "INSERT INTO users (ingame_username, role, created_at) VALUES ('$username', 'player', NOW())");
                $user_id = mysqli_insert_id($site_conn);
                $role    = 'player';
            } else {
                $user_data = mysqli_fetch_assoc($check);
                $user_id   = $user_data['id'];
                $role      = $user_data['role'];
            }

            $_SESSION['user_id']   = $user_id;
            $_SESSION['username']  = $username;
            $_SESSION['role']      = $role;
            $_SESSION['logged_in'] = true;

            if ($role == 'admin') {
                header("Location: pages/admin-dashboard.html");
            } else {
                header("Location: pages/my-tickets.html");
            }
            exit();
        } else {
            header("Location: login.php?error=wrong_password");
            exit();
        }
    } else {
        header('Location: login.php?error=account_not_found');
        exit();
    }
    }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<!-- باقي HTML هنا -->

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - MTA Support</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #0f0f14;
            --bg-secondary: #1a1a24;
            --bg-tertiary: #25253a;
            --accent-primary: #00d9ff;
            --accent-secondary: #7c3aed;
            --accent-discord: #5865F2;
            --accent-danger: #ef4444;
            --accent-success: #10b981;
            --text-primary: #e5e7eb;
            --text-secondary: #9ca3af;
            --border-color: #2d2d44;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background:
                radial-gradient(circle at 20% 30%, rgba(124, 58, 237, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(0, 217, 255, 0.15) 0%, transparent 50%);
            animation: backgroundMove 20s ease-in-out infinite alternate;
            z-index: 0;
        }

        @keyframes backgroundMove {
            0% {
                transform: translate(0, 0) rotate(0deg);
            }
            100% {
                transform: translate(50px, 50px) rotate(10deg);
            }
        }

        /* Grid Pattern */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                linear-gradient(rgba(0, 217, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 217, 255, 0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }

        .login-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(0, 217, 255, 0.1);
            animation: fadeInUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            font-size: 4rem;
            margin-bottom: 15px;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .logo h1 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }

        .logo p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .login-methods {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 30px 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .divider span {
            padding: 0 15px;
        }

        .btn {
            width: 100%;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-family: 'Cairo', sans-serif;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-discord {
            background: var(--accent-discord);
            color: white;
        }

        .btn-discord:hover {
            background: #4752c4;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(88, 101, 242, 0.4);
        }

        .btn-server {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: white;
        }

        .btn-server:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 217, 255, 0.4);
        }

        .btn-icon {
            font-size: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .btn-text {
            position: relative;
            z-index: 1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.95rem;
        }

        input {
            width: 100%;
            padding: 14px 18px;
            background: var(--bg-primary);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 1rem;
            font-family: 'Cairo', sans-serif;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(0, 217, 255, 0.1);
        }

        input::placeholder {
            color: var(--text-secondary);
        }

        .server-login-form {
            display: none;
            animation: fadeIn 0.5s ease-out;
        }

        .server-login-form.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .back-btn {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .back-btn:hover {
            background: var(--bg-tertiary);
            border-color: var(--accent-primary);
            color: var(--text-primary);
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .footer a {
            color: var(--accent-primary);
            text-decoration: none;
            font-weight: 600;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 40px 25px;
            }

            .logo h1 {
                font-size: 2rem;
            }

            .btn {
                padding: 14px 20px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="logo">
                <div class="logo-icon">🎮</div>
                <h1>MTA Support</h1>
                <p>مرحباً بك في نظام الدعم الفني</p>
            </div>

            <!-- طرق تسجيل الدخول -->
            <div class="login-methods" id="loginMethods">
                <button class="btn btn-discord" id="discordLoginBtn">
                    <span class="btn-icon">
                        <svg width="24" height="24" viewBox="0 0 127.14 96.36" fill="currentColor">
                            <path d="M107.7,8.07A105.15,105.15,0,0,0,81.47,0a72.06,72.06,0,0,0-3.36,6.83A97.68,97.68,0,0,0,49,6.83,72.37,72.37,0,0,0,45.64,0,105.89,105.89,0,0,0,19.39,8.09C2.79,32.65-1.71,56.6.54,80.21h0A105.73,105.73,0,0,0,32.71,96.36,77.7,77.7,0,0,0,39.6,85.25a68.42,68.42,0,0,1-10.85-5.18c.91-.66,1.8-1.34,2.66-2a75.57,75.57,0,0,0,64.32,0c.87.71,1.76,1.39,2.66,2a68.68,68.68,0,0,1-10.87,5.19,77,77,0,0,0,6.89,11.1A105.25,105.25,0,0,0,126.6,80.22h0C129.24,52.84,122.09,29.11,107.7,8.07ZM42.45,65.69C36.18,65.69,31,60,31,53s5-12.74,11.43-12.74S54,46,53.89,53,48.84,65.69,42.45,65.69Zm42.24,0C78.41,65.69,73.25,60,73.25,53s5-12.74,11.44-12.74S96.23,46,96.12,53,91.08,65.69,84.69,65.69Z"/>
                        </svg>
                    </span>
                    <span class="btn-text">تسجيل الدخول بـ Discord</span>
                </button>

                <div class="divider">
                    <span>أو</span>
                </div>

                <button class="btn btn-server" id="serverLoginBtn">
                    <span class="btn-icon">🎯</span>
                    <span class="btn-text">تسجيل الدخول بحساب السيرفر</span>
                </button>
            </div>

            <!-- فورم تسجيل الدخول بحساب السيرفر -->



            <form class="server-login-form" id="serverLoginForm" method="POST">
                <button type="button" class="btn back-btn" id="backBtn">
                    <span class="btn-text">← رجوع</span>
                </button>

                <div class="form-group">
                    <label>اسم المستخدم</label>
                    <input type="text" name="username" placeholder="ادخل اسم المستخدم في السيرفر" required>
                </div>

                <div class="form-group">
                    <label>كلمة المرور</label>
                    <input type="password" name="password" placeholder="ادخل كلمة المرور" required>
                </div>

                <button type="submit" class="btn btn-server" name="server-login">
                    <span class="btn-text">دخول</span>
                </button>
            </form>

            <div class="footer">
                مشاكل في تسجيل الدخول؟ <a href="#">تواصل معنا</a>
            </div>
        </div>
    </div>

    <script>
    const loginMethods = document.getElementById('loginMethods');
    const serverLoginForm = document.getElementById('serverLoginForm');
    const discordLoginBtn = document.getElementById('discordLoginBtn');
    const serverLoginBtn = document.getElementById('serverLoginBtn');
    const backBtn = document.getElementById('backBtn');

    // Discord Login
    discordLoginBtn.addEventListener('click', function() {
        window.location.href = 'auth/discord-login.php';
    });

    // Show Server Login Form
    serverLoginBtn.addEventListener('click', function() {
        loginMethods.style.display = 'none';
        serverLoginForm.classList.add('active');
    });

    // Back to Login Methods
    backBtn.addEventListener('click', function() {
        serverLoginForm.classList.remove('active');
        loginMethods.style.display = 'flex';
    });

    // 👈 شيلنا الـ Submit Handler - خلي الفورم يشتغل عادي POST
</script>
</body>
</html>
