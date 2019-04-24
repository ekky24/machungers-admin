<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeAtMaChungController extends Controller
{
    public function form() {
        return view('lifeatmachung.form');
    }
}
