<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>creating interface</title>
</head>
<body>
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Creation de poste') }}
            </h2>
        </x-slot>

        
        <form action="{{route('post.store')}}" method="POST" enctype="multipart/form-data" class="flex flex-col p-10 w-[600px]">
            @csrf
            @foreach ($errors->all() as $error)
                <div class="text-red-500 mt-2xx">
                    {{$error}}
                </div>
            @endforeach
            <x-input-label for="title"  class="text-xl">Title</x-input-label>
            <x-text-input name="title" id="title" ></x-text-input>

            <x-input-label for="content" class="mt-6 text-xl">Content</x-input-label>
            <x-text-input name="content" id="content"></x-text-input>
            
            <x-input-label for="image" class="mt-6 text-xl">Image</x-input-label>
            <x-text-input type="file" name="image" id="image"></x-text-input>

            <x-input-label for="category" class="text-xl mt-6">Category</x-input-label>
            <select name="category" id="category">
                @foreach ($categories as $category)
                    <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
        <x-primary-button class="mt-6 flex justify-center">ENVOYER</x-primary-button>
    </form>
</x-app-layout>
</body>
</html>