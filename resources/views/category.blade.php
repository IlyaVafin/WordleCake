<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <img width="200" height="200" src={{ "http://localhost:8000/storage/{$category->image}" }} alt="">
    <ul></ul>
    {{$games}}
    @foreach ($category->games as $game)
       
    @endforeach
</body>

</html>
