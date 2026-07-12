<?php

namespace App\Http\Controllers;

class ActivityController extends Controller
{
    public function index()
    {
        return redirect()->route('blog.category', 'activities', 301);
    }

    public function show($slug)
    {
        return redirect()->route('blog.detail', $slug, 301);
    }
}
