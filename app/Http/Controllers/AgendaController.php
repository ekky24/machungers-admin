<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function form() {
        return view('agenda.form');
    }
}
