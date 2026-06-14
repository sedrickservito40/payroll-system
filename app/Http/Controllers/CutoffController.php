<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class CutoffController extends Controller
{
   public function set(Request $request)
    {
        $request->validate([
            'cutoff_start' => 'required|date',
        ]);

        session()->put('cutoff_start', $request->cutoff_start);

        return back();
    }
    
    public function index()
        {
            return view('cutoff.index');
        }
}