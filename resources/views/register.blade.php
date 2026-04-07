<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(["resources/js/register.js"])
    <title>Document</title>
</head>

<body>
    
    <form class="form">
        @csrf
        <div class="">
            <label for="email">Email</label>
            <input autocomplete="email" required type="email" id="email" name="email">
        </div>
        <div class="">
            <label for="nickname">Nickname</label>
            <input required name="nickname" id="nickname" required type="text">
        </div>
        <div class="">
            <label for="password">Password</label>
            <input autocomplete="current-password" type="password" name="password" id="password">
        </div>
        <div class="">
            <label for="first_name">First name</label>
            <input required autocomplete="name" name="first_name" id="first_name" type="text">
        </div>
        <div class="">
            <label for="last_name">Last name</label>
            <input required type="text" name="last_name" id="last_name">
        </div>
        <div class="">
            <label for="birthday">Birthday</label>
            <input required type="date" id="birthday" name="birthday">
        </div>
        <div class="">
            <label for="avatar">Avatar</label>
            <input name="avatar" id="avatar" type="file">
        </div>
        <button class="submit-register">Register</button>
    </form>
</body>

</html>
