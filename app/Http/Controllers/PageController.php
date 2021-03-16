<?php

namespace App\Http\Controllers;

use App\Page;

class PageController extends Controller
{
    public function index($url)
    {
        $page = Page::where('slug', $url)->firstOrFail();
        return view('page.page', ['page' => $page]);
    }
}
