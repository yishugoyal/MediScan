<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"/>
<title>HealthWise - Your Trusted Medicine Information Source</title>
<link href="data:image/x-icon;base64," rel="icon" type="image/x-icon"/>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#1193d4",
            "background-light": "#f6f7f8",
            "background-dark": "#101c22",
          },
          fontFamily: {
            "display": ["Inter"]
          },
          borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
        },
      },
    }
  </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-gray-800 dark:text-gray-200">
<div class="flex flex-col min-h-screen">
<header class="bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm sticky top-0 z-50 border-b border-gray-200 dark:border-gray-800">
<nav class="container mx-auto px-6 py-4 flex items-center justify-between">
<div class="flex items-center gap-3">
<svg class="w-8 h-8 text-primary" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
<path d="M39.5563 34.1455V13.8546C39.5563 15.708 36.8773 17.3437 32.7927 18.3189C30.2914 18.916 27.263 19.2655 24 19.2655C20.737 19.2655 17.7086 18.916 15.2073 18.3189C11.1227 17.3437 8.44365 15.708 8.44365 13.8546V34.1455C8.44365 35.9988 11.1227 37.6346 15.2073 38.6098C17.7086 39.2069 20.737 39.5564 24 39.5564C27.263 39.5564 30.2914 39.2069 32.7927 38.6098C36.8773 37.6346 39.5563 35.9988 39.5563 34.1455Z" fill="currentColor"></path>
<path clip-rule="evenodd" d="M10.4485 13.8519C10.4749 13.9271 10.6203 14.246 11.379 14.7361C12.298 15.3298 13.7492 15.9145 15.6717 16.3735C18.0007 16.9296 20.8712 17.2655 24 17.2655C27.1288 17.2655 29.9993 16.9296 32.3283 16.3735C34.2508 15.9145 35.702 15.3298 36.621 14.7361C37.3796 14.246 37.5251 13.9271 37.5515 13.8519C37.5287 13.7876 37.4333 13.5973 37.0635 13.2931C36.5266 12.8516 35.6288 12.3647 34.343 11.9175C31.79 11.0295 28.1333 10.4437 24 10.4437C19.8667 10.4437 16.2099 11.0295 13.657 11.9175C12.3712 12.3647 11.4734 12.8516 10.9365 13.2931C10.5667 13.5973 10.4713 13.7876 10.4485 13.8519ZM37.5563 18.7877C36.3176 19.3925 34.8502 19.8839 33.2571 20.2642C30.5836 20.9025 27.3973 21.2655 24 21.2655C20.6027 21.2655 17.4164 20.9025 14.7429 20.2642C13.1498 19.8839 11.6824 19.3925 10.4436 18.7877V34.1275C10.4515 34.1545 10.5427 34.4867 11.379 35.027C12.298 35.6207 13.7492 36.2054 15.6717 36.6644C18.0007 37.2205 20.8712 37.5564 24 37.5564C27.1288 37.5564 29.9993 37.2205 32.3283 36.6644C34.2508 36.2054 35.702 35.6207 36.621 35.027C37.4573 34.4867 37.5485 34.1546 37.5563 34.1275V18.7877ZM41.5563 13.8546V34.1455C41.5563 36.1078 40.158 37.5042 38.7915 38.3869C37.3498 39.3182 35.4192 40.0389 33.2571 40.5551C30.5836 41.1934 27.3973 41.5564 24 41.5564C20.6027 41.5564 17.4164 41.1934 14.7429 40.5551C12.5808 40.0389 10.6502 39.3182 9.20848 38.3869C7.84205 37.5042 6.44365 36.1078 6.44365 34.1455L6.44365 13.8546C6.44365 12.2684 7.37223 11.0454 8.39581 10.2036C9.43325 9.3505 10.8137 8.67141 12.343 8.13948C15.4203 7.06909 19.5418 6.44366 24 6.44366C28.4582 6.44366 32.5797 7.06909 35.657 8.13948C37.1863 8.67141 38.5667 9.3505 39.6042 10.2036C40.6278 11.0454 41.5563 12.2684 41.5563 13.8546Z" fill="currentColor" fill-rule="evenodd"></path>
</svg>
<h1 class="text-xl font-bold text-gray-900 dark:text-white">MediScan</h1>
</div>
<div class="hidden md:flex items-center gap-8">
<a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="#">Medicine Info</a>
<a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="#">Dosage Calculator</a>
<a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="/about.html">About Us</a>
<a class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary transition-colors" href="/contactus.html">Contact</a>
</div>
<div class="flex items-center gap-2">
<button class="px-4 py-2 text-sm font-bold bg-primary/20 dark:bg-primary/30 text-primary rounded-lg hover:bg-primary/30 dark:hover:bg-primary/40 transition-colors"> <a href="/login.php">Log In </a></button>
<button class="px-4 py-2 text-sm font-bold bg-primary text-white rounded-lg hover:opacity-90 transition-opacity"><a href="/signup.php">Sign Up</a></button>
</div>
</nav>
</header>
<main class="flex-grow">
<section class="relative py-20 md:py-32">
<div class="absolute inset-0 bg-cover bg-center" style='background-image: linear-gradient(rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.6) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuDS6rQMHS019nUqo0WQGoeVKYmQeyh38E5-SYWuApeVbRcKfQCzW1BzKv8sScPbfmQTrBYfJasHGVm2-Lff_oz9ukoONly_nJkyJldssKOKf2kTqqS68H-bh9mM-unv-Tm4Qma6IGXHQlhuqb7iUpIIfgiJK2VDBJ6zgFe_nxhygsMS4ruPousuGzusEobBtnaWHOP7UiS5j7YmWvxG5w1vJIrYndg9pOpUb_4sbTMoeH-rqP04px3fTsiP4e7CriXfjUe7e6CzwK8");'></div>
<div class="relative container mx-auto px-6 text-center text-white">
<h1 class="text-4xl md:text-6xl font-black tracking-tighter leading-tight mb-4">Your Trusted Source for Medicine Information</h1>
<p class="text-base md:text-lg max-w-3xl mx-auto mb-8 text-gray-200">Access a comprehensive database of medicines and calculate dosages accurately for safe and effective treatment.</p>
<div class="flex flex-wrap justify-center gap-4">
<button class="px-6 py-3 text-base font-bold bg-primary text-white rounded-lg hover:opacity-90 transition-opacity"><a href="/login.php">Explore Medicine Info</a></button>
<button class="px-6 py-3 text-base font-bold bg-white/20 dark:bg-white/10 text-white rounded-lg hover:bg-white/30 dark:hover:bg-white/20 backdrop-blur-sm transition-colors"><a href="/login.php">Calculate Dosage</a></button>
</div>
</div>
</section>
<section class="py-16 md:py-24 bg-background-light dark:bg-background-dark">
<div class="container mx-auto px-6">
<div class="text-center max-w-3xl mx-auto mb-12">
<h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">Key Features</h2>
<p class="mt-4 text-lg text-gray-600 dark:text-gray-400">MediScan provides essential tools for managing your health and medications.</p>
</div>
<div class="grid md:grid-cols-3 gap-8">
<div class="bg-white dark:bg-background-dark/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 text-center">
<div class="flex justify-center mb-4">
<div class="bg-primary/10 dark:bg-primary/20 text-primary p-3 rounded-full">
<svg fill="currentColor" height="32" viewBox="0 0 256 256" width="32" xmlns="http://www.w3.org/2000/svg"><path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path></svg>
</div>
</div>
<h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Medicine Database</h3>
<p class="text-sm text-gray-600 dark:text-gray-400">Search for detailed information on thousands of medicines, including uses, side effects, and interactions.</p>
</div>
<div class="bg-white dark:bg-background-dark/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 text-center">
<div class="flex justify-center mb-4">
<div class="bg-primary/10 dark:bg-primary/20 text-primary p-3 rounded-full">
<svg fill="currentColor" height="32" viewBox="0 0 256 256" width="32" xmlns="http://www.w3.org/2000/svg"><path d="M200,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24ZM88,104h80V72H88Zm96,112H72a12,12,0,1,1,0-24h56a12,12,0,1,1,0,24Zm0-40H112a12,12,0,1,1,0-24h72a12,12,0,1,1,0,24Zm-16-72V40H200V56A16,16,0,0,0,184,72Z"></path></svg>
</div>
</div>
<h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Dosage Calculator</h3>
<p class="text-sm text-gray-600 dark:text-gray-400">Calculate the correct dosage for your specific needs, ensuring safe and effective medication use.</p>
</div>
<div class="bg-white dark:bg-background-dark/50 p-6 rounded-xl border border-gray-200 dark:border-gray-800 text-center">
<div class="flex justify-center mb-4">
<div class="bg-primary/10 dark:bg-primary/20 text-primary p-3 rounded-full">
<svg fill="currentColor" height="32" viewBox="0 0 256 256" width="32" xmlns="http://www.w3.org/2000/svg"><path d="M208,40H48A16,16,0,0,0,32,56v58.78c0,89.61,75.82,119.34,91,124.39a15.53,15.53,0,0,0,10,0c15.2-5.05,91-34.78,91-124.39V56A16,16,0,0,0,208,40Zm-80,155.15c-13.53-4.51-80-30.69-80-109.18V56H208V114.79C208,193.21,141.65,219.77,128,224.33V195.15Zm0-42.83l-56-56a8,8,0,0,1,11.32-11.32L112,113.31l28.69-28.68a8,8,0,0,1,11.31,11.31L129.66,136,168,174.34a8,8,0,0,1-11.32,11.32L128,157l-28.69,28.69a8,8,0,0,1-11.31-11.32Z"></path></svg>
</div>
</div>
<h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Safety &amp; Accuracy</h3>
<p class="text-sm text-gray-600 dark:text-gray-400">Our information is reviewed by medical professionals to ensure accuracy and reliability.</p>
</div>
</div>
</div>
</section>
<section class="py-16 md:py-24 bg-white dark:bg-background-dark/50">
<div class="container mx-auto px-6 text-center">
<h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white tracking-tight max-w-2xl mx-auto">Ready to Take Control of Your Health?</h2>
<p class="mt-4 text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Sign up for a free account to access all features and manage your medications effectively.</p>
<div class="mt-8">
<button class="px-8 py-4 text-base font-bold bg-primary text-white rounded-lg hover:opacity-90 transition-opacity"><a href="/login.php">Get Started </a></button>
</div>
</div>
</section>
</main>
<footer class="bg-background-light dark:bg-background-dark border-t border-gray-200 dark:border-gray-800">
<div class="container mx-auto px-6 py-8">
<div class="flex flex-col md:flex-row items-center justify-between gap-6">
<div class="flex flex-wrap justify-center gap-x-6 gap-y-2">
<a class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors" href="/about.html">About Us</a>
<a class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors" href="/contactus.html">Contact</a>
<a class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors" href="/privacy.html">Privacy Policy</a>
<a class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors" href="/tos.html">Terms of Service</a>
</div>
<p class="text-sm text-gray-600 dark:text-gray-400">© 2025 MediScan. All rights reserved.</p>
</div>
</div>
</footer>
</div>

</body></html>