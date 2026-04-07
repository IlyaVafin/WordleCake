<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @vite(['resources/js/sendMessage.js'])
</head>
<body>
    @if ($name == 'Ilya')
        <p> {{ $name }}</p>
    @else
        <p>Its not Ilya</p>
    @endif
    <form>
        @csrf
        <input type="text" name="message">
        <button>Submit</button>
    </form>
</body>

</html>
