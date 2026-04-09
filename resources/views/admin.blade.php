<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/js/category.js'])
    @vite(['resources/css/app.css'])
    <title>Document</title>
</head>

<body>
    <form class="create-category-form mb-4 flex items-center justify-center flex-col w-full">
        <div class="flex flex-col gap-2 max-w-md w-full">
            <label for="">Category title</label>
            <input name="name" type="text">
        </div>
        <div class="flex flex-col gap-2 max-w-md w-full">
            <label for="">Category description</label>
            <textarea name="description" name="" id=""></textarea>
        </div>
        <div class="flex flex-col gap-2 max-w-md w-full">
            <label for="image">Poster</label>
            <input name="image" type="file" id="image">
        </div>
        <button class="bg-black text-white pt-1 pb-1 pr-4 pl-4 mt-4">Create category</button>
    </form>
    <input class="search-category" type="search">
    <ul class="categories flex flex-wrap gap-6 items-center justify-center"></ul>
    <div class="flex mt-4 gap-2">
        <button class="prev-button bg-black text-white pt-1 pb-1 pl-4 pr-4 rounded-xl w-25">Prev</button>
        <button class="next-button bg-black text-white pt-1 pb-1 pl-4 pr-4 rounded-xl w-25">Next</button>
    </div>
</body>

</html>
