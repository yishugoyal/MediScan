<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>MediScan - Forgot Password</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": {
                "DEFAULT": "#4F46E5",
                "light": "#A5B4FC"
            },
            "secondary": "#7C3AED",
            "background-light": "#F5F3FF",
            "background-dark": "#111827",
            "card-dark": "#1F2937"
          },
          fontFamily: {
            "display": ["Lexend"]
          },
          borderRadius: {"DEFAULT": "0.5rem", "lg": "0.75rem", "xl": "1rem", "full": "9999px"},
        },
      },
    }
  </script>
<style type="text/tailwindcss">
    @layer base {
      body {
        @apply font-display bg-background-light dark:bg-background-dark;
      }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-gray-900 dark:to-purple-900/50">
<div class="flex flex-col min-h-screen">
<header class="w-full">
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between py-6">
<div class="flex items-center gap-2 text-zinc-900 dark:text-white">
<div class="w-10 h-6 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center">
<svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M12 5V19"></path>
<path d="M5 12H19"></path>
</svg>
</div>
<h1 class="text-2xl font-bold">MediScan</h1>
</div>
</div>
</div>
</header>
<main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
<div class="w-full max-w-md space-y-8 bg-white/70 dark:bg-card-dark/80 backdrop-blur-xl p-8 rounded-2xl shadow-2xl shadow-purple-500/10">
<div class="text-center">
<div class="flex justify-center items-center mb-4">
<div class="w-12 h-8 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center mr-4">
<svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
<path d="M12 5V19"></path>
<path d="M5 12H19"></path>
</svg>
</div>
<h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Forgot Password?</h2>
</div>
<p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
            No worries, we'll send you reset instructions.
          </p>
</div>
<form action="#" class="mt-8 space-y-6" method="POST">
<div class="rounded-xl shadow-sm -space-y-px">
<div>
<label class="sr-only" for="email-address">Email address</label>
<input autocomplete="email" class="appearance-none rounded-xl relative block w-full px-4 py-3 border border-purple-200 dark:border-purple-800 placeholder-zinc-500 dark:placeholder-zinc-400 text-zinc-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent focus:z-10 sm:text-sm bg-background-light dark:bg-gray-800" id="email-address" name="email" placeholder="Enter your email address" required="" type="email"/>
</div>
</div>
<div>
<button class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-gradient-to-r from-primary to-secondary hover:from-primary/90 hover:to-secondary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-background-light dark:focus:ring-offset-card-dark focus:ring-primary" type="submit">
              Send Reset Link
            </button>
</div>
</form>
<div class="text-center">
<p class="text-sm">
<a class="font-medium text-primary hover:text-primary/80" href="login.html">
              Back to Login
            </a>
</p>
</div>
</div>
</main>
<footer class="w-full py-6">
<div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
<p class="text-sm text-zinc-500 dark:text-zinc-400">© 2025 MediScan. All rights reserved.</p>
</div>
</footer>
</div>

</body></html>