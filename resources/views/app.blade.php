<!DOCTYPE html>
<html class="h-full" data-theme="true" data-theme-mode="light" lang="en">
 <head>
     <meta charset="utf-8"/>
     <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"/>

     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
     @vite('resources/css/app.scss')

     <link rel="icon" type="image/png" href="{{ asset('favicon.svg') }}">
     <meta name="msapplication-TileColor" content="#da532c">

     <style>
         #app {
             height: 100%;
         }
     </style>
     @routes
     @inertiaHead
 </head>

 <body class="h-full demo1 dark:bg-coal-500 sidebar-fixed header-fixed">

 <script type="text/javascript">
     window.locale = '{{ app()->getLocale() }}';
     const defaultThemeMode = 'system'; // light|dark|system
     let themeMode;

     if (document.documentElement) {
         if (localStorage.getItem('theme')) {
             themeMode = localStorage.getItem('theme');
         } else if (document.documentElement.hasAttribute('data-theme-mode')) {
             themeMode = document.documentElement.getAttribute('data-theme-mode');
         } else {
             themeMode = defaultThemeMode;
         }

         if (themeMode === 'system') {
             themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
         }

         document.documentElement.classList.add(themeMode);
     }
 </script>

 @inertia

 @vite(['resources/js/app.js'])
 </body>
</html>
