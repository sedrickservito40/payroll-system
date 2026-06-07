<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DtrController extends Controller
{
    public function index()
    {
        return view('dtr.index');
    }
}