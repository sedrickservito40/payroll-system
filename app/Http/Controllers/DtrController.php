<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dtr;

class DtrController extends Controller
{
    public function index()
    {
        $dtrs = Dtr::orderBy('date', 'desc')->get();

        return view('dtr.index', compact('dtrs'));
    }
}