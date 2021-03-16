<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DevelopersController extends Controller
{
    public function index()
    {
        return view('developers.index');
    }

    public function links()
    {
        return view('developers.links.index');
    }

    public function spaces()
    {
        return view('developers.spaces.index');
    }

    public function domains()
    {
        return view('developers.domains.index');
    }

    public function account()
    {
        return view('developers.account.index');
    }
}
