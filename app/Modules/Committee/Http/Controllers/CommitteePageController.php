<?php

namespace App\Modules\Committee\Http\Controllers;

use Illuminate\Routing\Controller;

class CommitteePageController extends Controller
{
    public function index()
    {
        return view('committee::index');
    }
}
