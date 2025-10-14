<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>MediScan - Health Profile</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms"></script>
<script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#11b4d4",
            "background-light": "#f6f8f8",
            "background-dark": "#101f22",
          },
          fontFamily: {
            "display": ["Inter"]
          },
          borderRadius: {
            "DEFAULT": "0.25rem",
            "lg": "0.5rem",
            "xl": "0.75rem",
            "full": "9999px"
          },
        },
      },
    }
  </script>
<style>
    .form-select {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
      background-position: right 0.5rem center;
      background-repeat: no-repeat;
      background-size: 1.5em 1.5em;
      padding-right: 2.5rem;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }
  </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex flex-col min-h-screen">
<header class="bg-background-light dark:bg-background-dark border-b border-gray-200 dark:border-gray-800 shadow-sm">
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between h-16">
<div class="flex items-center gap-4">
<svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
</svg>
<h1 class="text-xl font-bold text-gray-900 dark:text-white">MediScan</h1>
</div>
<nav class="hidden md:flex items-center gap-8">
<a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="#">Home</a>
<a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="#">Medicines</a>
<a class="text-sm font-medium text-primary dark:text-primary" href="#">Profile</a>
</nav>
<div class="flex items-center gap-4">
<button class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
<svg fill="currentColor" height="24" viewBox="0 0 256 256" width="24" xmlns="http://www.w3.org/2000/svg">
<path d="M221.8,175.94C216.25,166.38,208,139.33,208,104a80,80,0,1,0-160,0c0,35.34-8.26,62.38-13.81,71.94A16,16,0,0,0,48,200H88.81a40,40,0,0,0,78.38,0H208a16,16,0,0,0,13.8-24.06ZM128,216a24,24,0,0,1-22.62-16h45.24A24,24,0,0,1,128,216ZM48,184c7.7-13.24,16-43.92,16-80a64,64,0,1,1,128,0c0,36.05,8.28,66.73,16,80Z"></path>
</svg>
</button>
<div class="w-10 h-10 rounded-full bg-cover bg-center" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDBtWTAKcQUfJpOeXjDbVz_Q7RVJGy8256XYheGQWdRbcDFmqc-M9WFkopTp5AO3fTb1UX1_vfnip1-FzMiiGGVs2UTehAtaW5qozoEaRD754luISm7YhnL5Zd_q1aNVVExBpqHgGdIuDlRhjbUwNoIWz89vOnpQiOmqFc3U1ZYrQvOM5_ikWjBP9EjPNlMG1230mLD9NJgwxpbJl3yBY_4iZKVaMOR2gKc024IQqJjK6kyzyJWt7GPX7YJRHYocj7AUML8HyqiUG7S");'></div>
</div>
</div>
</div>
</header>
<main class="flex-grow">
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
<div class="max-w-3xl mx-auto bg-white dark:bg-gray-900/50 p-6 sm:p-8 rounded-xl shadow-lg">
<div class="mb-8 text-center">
<h2 class="text-3xl font-bold text-gray-900 dark:text-white">Your Health Profile</h2>
<p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Keep your information up to date for personalized insights.</p>
</div>
<form class="space-y-6">
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="age">Age</label>
<input class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-primary focus:border-primary text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" id="age" name="age" placeholder="e.g., 34" type="number"/>
</div>
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="gender">Gender</label>
<select class="form-select mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-primary focus:border-primary text-gray-900 dark:text-white" id="gender" name="gender">
<option>Select your gender</option>
<option>Male</option>
<option>Female</option>
<option>Other</option>
</select>
</div>
</div>
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="pregnancy">Pregnancy Status</label>
<select class="form-select mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-primary focus:border-primary text-gray-900 dark:text-white" id="pregnancy" name="pregnancy">
<option>Select status</option>
<option>Not Pregnant</option>
<option>Pregnant</option>
<option>Trying to conceive</option>
</select>
</div>
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="diseases">Common Diseases</label>
<input class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-primary focus:border-primary text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" id="diseases" name="diseases" placeholder="e.g., Diabetes, Asthma" type="text"/>
<p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Separate multiple diseases with a comma.</p>
</div>
<div>
<label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="allergies">Allergies</label>
<input class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-primary focus:border-primary text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500" id="allergies" name="allergies" placeholder="e.g., Penicillin, Peanuts" type="text"/>
<p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Separate multiple allergies with a comma.</p>
</div>
<div class="pt-4">
<button class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary/50 dark:focus:ring-offset-background-dark transition-colors duration-300" type="submit">
                Save Profile
              </button>
</div>
</form>
</div>
</div>
</main>
</div>

</body></html>