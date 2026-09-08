<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Not Found</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center bg-base-200 text-center p-4">
        <h1 class="text-6xl font-bold text-base-content/30">404</h1>
        <p class="text-lg mt-2 mb-6">The page you’re looking for doesn’t exist.</p>
        <a href="/" class="btn btn-primary">Go home</a>
    </div>
</body>
</html>