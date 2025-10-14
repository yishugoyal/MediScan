<?php
session_start();
require_once __DIR__ . '/config.php';

/* --- Helper Functions --- */
if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verify_csrf')) {
    function verify_csrf($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('db')) {
    function db() {
        static $pdo;
        if ($pdo === null) {
            $dsn = 'mysql:host=localhost;dbname=mediscan;charset=utf8mb4';
            $pdo = new PDO($dsn, 'root', '', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return $pdo;
    }
}

/* --- Handle AJAX Login --- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');

    if (!verify_csrf($_POST['csrf'] ?? '')) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid CSRF token']);
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
        exit;
    }

    $stmt = db()->prepare('SELECT id, name, email, password_hash FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $u = $stmt->fetch();

    if ($u && password_verify($password, $u['password_hash'])) {
        if (password_needs_rehash($u['password_hash'], PASSWORD_DEFAULT)) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $up = db()->prepare('UPDATE users SET password_hash=? WHERE id=?');
            $up->execute([$newHash, $u['id']]);
        }

        $_SESSION['user_id'] = (int)$u['id'];
        $_SESSION['user_name'] = $u['name'];

        if (!empty($_POST['remember_me'])) {
            setcookie('mediscan_remember', (string)$u['id'], [
                'expires' => time() + (60 * 60 * 24 * 14),
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }

        echo json_encode(['status' => 'success', 'message' => 'Login successful! Redirecting...']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    }
    exit;
}

$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>MediScan Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Fredoka+One&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
<link rel="stylesheet" href="assets/css/login.css">
<script>
tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "primary": "#7f13ec",
                "background-light": "#ffffff",
                "background-dark": "#191022",
            },
            fontFamily: {
                "display": ["Manrope"],
                "playful": ["Fredoka One", "cursive"],
                "heading": ["Poppins", "sans-serif"],
            },
        },
    },
}
</script>
<style>
body { font-family: 'Manrope', sans-serif; }
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
.custom-checkbox {
    appearance: none;
    cursor: pointer;
    width: 1.25rem; height: 1.25rem;
    border-radius: 0.25rem;
    background-color: rgba(255,255,255,0.3);
    border: 1px solid rgba(255,255,255,0.5);
    position: relative;
    transition: 0.2s;
}
.custom-checkbox:checked {
    background-color: white; border-color: white;
}
.custom-checkbox:checked::after {
    content: '✓'; font-size: 1rem; color: #7f13ec;
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
}
</style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display">
<div class="flex h-screen w-full">

    <div class="relative hidden lg:flex flex-1 items-center justify-center p-12 bg-white">
        <div class="relative z-10 max-w-md text-center">
            <h1 class="text-7xl font-playful">
                <span class="text-black">Welcome to</span>
                <span class="bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">MediScan</span>
            </h1>
            <p class="mt-4 text-lg text-gray-600">your journey starts here</p>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
        <div class="w-full max-w-md bg-gradient-to-br from-blue-500 to-purple-600 p-8 rounded-xl shadow-[0_0_40px_rgba(192,132,252,0.8),_0_0_40px_rgba(96,165,250,0.8)]">

            <h2 class="text-4xl font-heading text-white text-center font-bold">Welcome Back</h2>
            <p class="text-center text-white text-lg mt-2 mb-8">Let's start your journey with MediScan</p>

            <div id="msg" class="hidden mb-4 p-3 text-center rounded-lg"></div>

            <form id="loginForm" class="space-y-6">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>"/>

                <div>
                    <label for="email" class="block text-sm font-medium text-white">Email</label>
                    <input id="email" name="email" type="email" required
                        class="form-input block w-full rounded-lg bg-white/20 text-white placeholder-gray-300 focus:ring-white focus:border-white"
                        placeholder="Enter your email"/>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-white">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required
                            class="form-input block w-full rounded-lg bg-white/20 text-white placeholder-gray-300 focus:ring-white focus:border-white pr-10"
                            placeholder="Enter your password"/>
                        <button type="button" onclick="togglePasswordVisibility()" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <span class="material-symbols-outlined text-gray-300" id="eye-icon">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <input class="custom-checkbox" id="remember-me" name="remember_me" type="checkbox"/>
                        <label class="ml-2 block text-sm text-white" for="remember-me">Remember Me</label>
                    </div>
                    <a class="font-medium text-white underline hover:text-gray-200" href="forgot-password.php">Forgot Password?</a>
                </div>

                <button type="submit"
                    class="w-full py-3 px-4 rounded-lg text-lg font-bold text-purple-600 bg-white hover:bg-gray-200 shadow-md transition-transform transform hover:scale-105">
                    Log in
                </button>
            </form>

            <div class="mt-8 text-center text-white">
                Don’t have an account? 
                <a href="signup.php" class="font-medium hover:underline">Sign Up</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}

document.getElementById('loginForm').addEventListener('submit', async function (e) {
    e.preventDefault(); // prevent refresh

    const form = e.target;
    const msgBox = document.getElementById('msg');
    const formData = new FormData(form);
    formData.append('ajax', '1');

    msgBox.classList.remove('hidden', 'bg-red-500', 'bg-green-500');
    msgBox.textContent = '⏳ Logging in...';
    msgBox.classList.add('bg-blue-500', 'text-white');

    try {
        const res = await fetch('login.php', { method: 'POST', body: formData });
        const data = await res.json();

        msgBox.classList.remove('bg-blue-500');
        if (data.status === 'success') {
            msgBox.classList.add('bg-green-500');
            msgBox.textContent = data.message;
            setTimeout(() => window.location.href = 'dashboard.php', 1000);
        } else {
            msgBox.classList.add('bg-red-500');
            msgBox.textContent = data.message;
        }
    } catch (err) {
        msgBox.classList.add('bg-red-500');
        msgBox.textContent = 'Something went wrong. Please try again.';
    }
});
</script>
</body>
</html>
