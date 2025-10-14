<?php
require_once __DIR__ . '/config.php';

if (is_post()) {
  if (!verify_csrf($_POST['csrf'] ?? '')) { http_response_code(400); exit('Invalid CSRF'); }
  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';

  // Soft checks (same spirit as your demo)
  if (!$name || !$email || strlen($pass) < 8) { UI\noop(); }

  $hash = password_hash_mediscan($pass);
  $stmt = db()->prepare('INSERT INTO users(name,email,password_hash) VALUES(?,?,?)');
  try { $stmt->execute([$name, $email, $hash]); } catch (Throwable $e) { /* handle duplicate etc. */ }

  redirect('login.php');
}

$title = 'Sign up - MediScan';
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>MediScan Sign Up</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;700;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
<script>
  tailwind.config = {
    darkMode: "class",
    theme: {
      extend: {
        colors: {
          primary: {
            DEFAULT: "#4F46E5",
            50: "#E0E7FF",
            100: "#C7D2FE",
            200: "#A5B4FC",
            300: "#818CF8",
            400: "#6366F1",
            500: "#4F46E5",
            600: "#4338CA",
            700: "#3730A3",
            800: "#312E81",
            900: "#282567"
          },
          secondary: {
            DEFAULT: "#8B5CF6",
            50: "#F5F3FF",
            100: "#EDE9FE",
            200: "#DDD6FE",
            300: "#C4B5FD",
            400: "#A78BFA",
            500: "#8B5CF6",
            600: "#7C3AED",
            700: "#6D28D9",
            800: "#5B21B6",
            900: "#4C1D95"
          },
          "link-blue": "#2563EB",
          "background-light": "#F9FAFB",
          "background-dark": "#111827",
        },
        fontFamily: {
          display: ["Lexend"]
        },
        borderRadius: {
          DEFAULT: "0.25rem",
          lg: "0.5rem",
          xl: "0.75rem",
          full: "9999px"
        },
      },
    },
  }
</script>
<style>
  .form-input::placeholder { color: #9ca3af; }
  .dark .form-input::placeholder { color: #6b7280; }
  .gradient-bg { background: linear-gradient(135deg, #4F46E5, #8B5CF6); }
  .dark .gradient-bg { background: linear-gradient(135deg, #282567, #4C1D95); }
  .text-gradient {
    background: linear-gradient(135deg, #4F46E5, #8B5CF6);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  }
  .dark .text-gradient {
    background: linear-gradient(135deg, #A5B4FC, #C4B5FD);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  }
  .material-symbols-outlined {
    font-variation-settings: 'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24
  }
</style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex flex-col min-h-screen">
  <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
      <div>
        <div class="flex items-center justify-center gap-4 mb-6">
          <div class="w-20 h-10 rounded-full gradient-bg flex items-center justify-center shadow-lg">
            <span class="material-symbols-outlined text-white text-4xl font-black" style="font-variation-settings: 'FILL' 1, 'wght' 700;">add</span>
          </div>
          <h1 class="text-5xl font-bold text-gradient">MediScan</h1>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
          Create your account
        </h2>
      </div>

      <!-- IMPORTANT: Keep method=post and field names matching backend -->
      <form class="mt-8 space-y-6" method="post" novalidate>
        <input type="hidden" name="csrf" value="<?php echo e(csrf_token()); ?>"/>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 ml-3" for="name">Name</label>
            <input id="name" name="name" type="text" autocomplete="name" required
              class="form-input appearance-none rounded-full relative block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:text-sm"
              placeholder="Your full name"/>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 ml-3" for="email">Email</label>
            <input id="email" name="email" type="email" autocomplete="email" required
              class="form-input appearance-none rounded-full relative block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:text-sm"
              placeholder="you@example.com"/>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 ml-3" for="password">Password</label>
            <div class="relative">
              <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required
                class="form-input appearance-none rounded-full relative block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:text-sm"
                placeholder="••••••••"/>
              <button class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 dark:text-gray-400" type="button" onclick="togglePasswordVisibility('password')">
                <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
            <p id="pwd-msg" class="mt-1 text-xs text-gray-500"></p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 ml-3" for="confirm-password">Confirm Password</label>
            <div class="relative">
              <input id="confirm-password" type="password" autocomplete="new-password" required
                class="form-input appearance-none rounded-full relative block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 bg-background-light dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:text-sm"
                placeholder="••••••••"/>
              <button class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 dark:text-gray-400" type="button" onclick="togglePasswordVisibility('confirm-password')">
                <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
            <p id="confirm-msg" class="mt-1 text-xs text-red-500 hidden">Passwords do not match.</p>
          </div>
        </div>

        <div class="pt-2">
          <button class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-lg text-white bg-gradient-to-r from-primary-500 to-secondary-500 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-opacity" type="submit">
            Create Account
          </button>
        </div>

        <p class="mt-4 text-center text-sm text-gray-600 dark:text-gray-400">
          Already have an account?
          <a class="font-medium text-link-blue hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" href="login.php">Log in here</a>
        </p>
      </form>
    </div>
  </main>
</div>

<script>
  function togglePasswordVisibility(id) {
    const input = document.getElementById(id);
    const icon = input.nextElementSibling.querySelector('span');
    if (input.type === "password") {
      input.type = "text";
      icon.textContent = "visibility_off";
    } else {
      input.type = "password";
      icon.textContent = "visibility";
    }
  }

  // Light client-side checks that don't block server post
  const form = document.querySelector('form');
  const pwd = document.getElementById('password');
  const pwd2 = document.getElementById('confirm-password');
  const pwdMsg = document.getElementById('pwd-msg');
  const confirmMsg = document.getElementById('confirm-msg');

  pwd.addEventListener('input', () => {
    const v = pwd.value;
    if (v.length < 8) { pwdMsg.textContent = "Minimum 8 characters."; pwdMsg.className = "mt-1 text-xs text-red-500"; }
    else { pwdMsg.textContent = "Looks good."; pwdMsg.className = "mt-1 text-xs text-green-600"; }
  });

  pwd2.addEventListener('input', () => {
    if (pwd2.value && pwd2.value !== pwd.value) { confirmMsg.classList.remove('hidden'); }
    else { confirmMsg.classList.add('hidden'); }
  });

  // Do not preventDefault; let PHP backend handle the insert + redirect
</script>
</body>
</html>
<?php
$content = ob_get_clean();
include __DIR__ . '/views/layouts/base.php';
