<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <style>
        @import url({{asset('css/login.css')}});
    </style>
</head>
<body>
    <div class="container">
        <nav>
            <img class="logo" src="{{ asset('./img/logo.png') }}" alt="logo">
            @guest
            <ul>
                <a href="/login"><li><button type="button" class="login btn btn-outline-danger">Login</button></li></a>
                <a href="/register"><li><button type="button" class="Register btn btn-outline-danger">Register</button></li></a>
            </ul>
            @endguest
        </nav>
        <div class="col-6 mx-auto my-5 mt-1">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title text-center">Login</h1>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3 mt-3">
                            <label for="exampleInputEmail1" class="form-label">Email:</label>
                            <input id="email" name="email" :value="old('email')" required autofocus autocomplete="username" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password:</label>
                            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control">
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label" for="remember_me">Remember me</label>
                        </div>
                            <div>
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <br><p>Don't have an account? <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="/register">
                        {{ __('Sign up') }}
                    </a></p>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
