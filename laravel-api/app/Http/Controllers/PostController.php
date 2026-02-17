<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        return 'Post with no params';
    }

    public function show(Request $request, $id) {
        $data = [
            'id' => $id,
            'title' => "Post ${id}",
            'query' => $request->query(),
        ];
        return view('post.show', $data);
    }
}
