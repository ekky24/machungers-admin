<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Storage;
use Hash;

class SessionController extends Controller
{
	private $ref;
    private $database;

    public function __construct() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $this->database = $firebase->getDatabase();
        $this->ref = $this->database->getReference('user');
        return $this->middleware('guest')->except(['logout', 'ubah_pass', 'store_pass']);
    }

    public function create() {
    	return view('session.login');
    }

    public function store(Request $request) {
    	$this->validate($request , [
            'username' => 'required',
            'password' => 'required',
        ]);

    	$data = $this->ref->getValue();

        foreach ($data as $key => $row) {
            if($row['username'] == $request->input('username') and $row['password'] == $request->input('password')) {
            	$request->session()->put('authenticated', $key);
            	return redirect('/');
            }
        }

        return back()->withErrors([
	    	'message' => 'Username atau password yang anda masukkan salah.'
	   	]);
    }

    public function logout() {
    	auth()->logout();
    	return redirect('/');
    }
}
