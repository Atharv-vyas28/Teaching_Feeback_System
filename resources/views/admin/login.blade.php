<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — {{ config('app.name', 'LaraCopilot') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center bg-base-200 p-4">
        <div class="card w-full max-w-md bg-base-100 shadow-xl">
            <div class="card-body">
                <h1 class="card-title justify-center text-2xl mb-2">Admin Login</h1>

                <div class="alert alert-info text-sm mb-4">
                    <span>Demo credentials: <code>admin@business.com</code> / <code>admin123</code></span>
                </div>

                @if ($errors->any())
                    <div class="alert alert-error text-sm mb-2">
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="/admin/login" class="space-y-4">
                    @csrf
                    <div class="form-control">
                        <label class="label" for="email"><span class="label-text">Email</span></label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                               class="input input-bordered w-full" />
                    </div>
                    <div class="form-control">
                        <label class="label" for="password"><span class="label-text">Password</span></label>
                        <input id="password" name="password" type="password" required
                               class="input input-bordered w-full" />
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>