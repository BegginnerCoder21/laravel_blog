<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editer {{$post->title}}
        </h2>
    </x-slot>
    @foreach ($errors->all() as $error)
        <span> {{$error}} </span>
    @endforeach
    <form action="{{route('post.update',$post)}}" method="post" enctype="multipart/form-data" class="flex flex-col p-10 w-[600px]">
        @csrf
        @method('PUT')
        <x-input-label for="title"  class="text-xl">Title</x-input-label>
        <x-text-input name="title" id="title" value="{{$post->title}}">  </x-text-input>

        <x-input-label for="content" class="mt-6 text-xl">Content</x-input-label>
        <x-text-input name="content" id="content" value="{{$post->content}}"> </x-text-input>
        
        <x-input-label for="image" class="mt-6 text-xl">Image</x-input-label>
        <x-text-input type="file" name="image" id="image"></x-text-input>

        <x-input-label for="category" class="text-xl mt-6">Category</x-input-label>
        <select name="category" id="category">
            @foreach ($categories as $category)
                <option value="{{$category->id}}" {{$category->id == $post->category_id ? 'selected' : ''}}>{{$category->name}}</option>
            @endforeach
        </select>
    <x-primary-button class="mt-6 flex justify-center">ENVOYER</x-primary-button>
</x-app-layout>