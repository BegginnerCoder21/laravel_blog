<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user','category')->latest()->get();
        
        return view('posts.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('posts.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $imagePath = $request->image->store('postImages');
        Post::create([
            'title' =>$request->title,
            'content' => $request->content,
            'images' => $imagePath
        ]);
        return redirect()->route('post.index')->with('successCreating','votre article a bien été crée');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {   dd($post);
        $categories = Category::all();
        return view('posts.edit',compact('post','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {    
        if(!$request->image){
            $imageRequest = $post->images;
        }else{
            $imageRequest = $request->image->store('postImages');
        }
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'images' => $imageRequest
        ]);

       return redirect()->route('post.index')->with('success','votre poste a bien été modifié');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete(); 

        return redirect()->route('post.index')->with('successSup','votre poste a bien été supprimé');
    }
}
