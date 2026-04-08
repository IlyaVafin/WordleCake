<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(["resources/js/createCategory.js"])
    <title>Document</title>
</head>
<body>
    <form class="create-category-form">
        @csrf
        <div class="">
            <label for="">Category title</label> <br>
            <input name="title" type="text">
        </div>
        <div class="">
            <label for="">Category description</label> <br>
            <textarea name="description" name="" id=""></textarea>
        </div>
        <div>
            <label for="image">Poster</label> <br>
            <input name="image" type="file" id="image">
        </div>
        <button>Create category</button>
    </form>
</body>
</html>
