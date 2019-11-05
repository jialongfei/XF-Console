<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * main page
     */
    public function index()
    {
        return view('Index.Index');
    }
}
