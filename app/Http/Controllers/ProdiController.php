<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdiController extends Controller
{
    public function form() {
        return view('prodi.form');
    }
}
