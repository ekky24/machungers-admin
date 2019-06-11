<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
	public function __construct() {
		$this->middleware('usersession');
	}

    public function form() {
        return view('user.form');
    }
}
