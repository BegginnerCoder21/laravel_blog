<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="p-10 space-y-6">
        @foreach ($posts as $post)
            <div class="flex h-10 w-[50%] text-xl bg-white">
               <a href="{{route('post.edit',$post)}}" class="bg-orange-300 flex-auto rounded-l-md flex items-center px-2">Editer {{$post->title}}</a> 
               <a href="#"
               class="bg-red-400 flex-auto rounded-r-md flex items-center px-2" 
               onclick="event.preventDefault();
                    document.getElementById('deletePost{{$post->id}}').submit();
                    "
                    >   
                    
                    <form action="{{route('post.destroy',$post->id)}}" method="post" id="deletePost{{$post->id}}">
                        @csrf
                        @method('delete')
                       
                        Supprimer {{$post->title}}
                    </form>
                </a>
            </div>
        @endforeach
    </div>
</x-app-layout>
