<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/js/login.js'])
</head>

<body>
    <form class="login-form">
        @csrf
        <div>
            <label for="email">Email</label>
            <input name="email" id="email" type="email">
        </div>
        <div>
            <label for="password">Password</label>
            <input name="password" id="password" type="password">
        </div>
        <button>Log in</button>
        @error('password')
            <p>{{ $message }}</p>
        @enderror
        @error('email')
            <p>{{ $message }}</p>
        @enderror
    </form>
</body>

</html>
