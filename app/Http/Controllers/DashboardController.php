<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $posts = auth()->user()->posts;
        return view('dashboard',compact('posts'));
    }
}
