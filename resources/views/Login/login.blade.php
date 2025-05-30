<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'KOMPLEMON') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        /* Basic styles for the login page */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f3f4f6;
        }
        .min-h-screen {
            min-height: 100vh;
        }
        .flex {
            display: flex;
        }
        .flex-col {
            flex-direction: column;
        }
        .items-center {
            align-items: center;
        }
        .justify-center {
            justify-content: center;
        }
        .w-full {
            width: 100%;
        }
        .max-w-md {
            max-width: 28rem;
        }
        .mt-6 {
            margin-top: 1.5rem;
        }
        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .bg-white {
            background-color: white;
        }
        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .text-center {
            text-align: center;
        }
        .mb-4 {
            margin-bottom: 1rem;
        }
        .text-2xl {
            font-size: 1.5rem;
        }
        .font-bold {
            font-weight: bold;
        }
        .mt-4 {
            margin-top: 1rem;
        }
        .block {
            display: block;
        }
        .font-medium {
            font-weight: 500;
        }
        .text-sm {
            font-size: 0.875rem;
        }
        .text-gray-700 {
            color: #374151;
        }
        .input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            margin-top: 0.25rem;
        }
        .btn {
            display: inline-block;
            width: 100%;
            padding: 0.5rem 1rem;
            background-color: #059669;
            color: white;
            border: none;
            border-radius: 0.375rem;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #047857;
        }
        .text-red-500 {
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="w-full max-w-md mt-6 px-6 py-4 bg-white shadow-md rounded-lg">
            <div class="mb-4 text-center">
                <h2 class="text-2xl font-bold">KOMPLEMON Admin</h2>
            </div>

            @if(session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 text-red-500">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label class="block font-medium text-sm text-gray-700" for="email">
                        Email
                    </label>
                    <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required autofocus />
                </div>

                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700" for="password">
                        Password
                    </label>
                    <input id="password" class="input" type="password" name="password" required />
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300" name="remember">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="btn">
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>