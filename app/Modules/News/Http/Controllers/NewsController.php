<?php

namespace App\Modules\News\Http\Controllers;

use Illuminate\Routing\Controller;

class NewsController extends Controller
{
    public function index()
    {
        return view('news::index');
    }
}


