<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies2U</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</head>
<body>
    <div class="main">
        <nav>
            <img class="logo" src="{{ asset('./img/logo_V.3.png') }}" alt="logo">
            @guest
            <ul>
                <a href="/login"><li><button type="button" class="btn btn-outline-danger">Login</button></li></a>
                <a href="/register"><li><button type="button" class="btn btn-outline-danger">Register</button></li></a>
            </ul>
            @endguest
        </nav>
        <h1>The Movies2U <br>website movie for you.</h1>
        <a href="/home"><button type="button" class="start">Watch Now</button></a>
    </div>
</body>
</html>
