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
        return $this->middleware('usersession')->except(['create', 'store']);
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
                $data = $this->database->getReference('user/' . $key)->getValue();
                $data['key'] = $key;
            	$request->session()->put('authenticated', $data);

                date_default_timezone_set('Asia/Jakarta');
                $now = date('d/m/Y h:i:s a', time());
                $this->ref->getChild($key)->set([
                    'username' => $data['username'],
                    'password' => $data['password'],
                    'level' => $data['level'],
                    'kontak' => $data['kontak'],
                    'last_login' => $now,
                ]);
            	return redirect('/');
            }
        }

        return back()->withErrors([
	    	'message' => 'Username atau password yang anda masukkan salah.'
	   	]);
    }

    public function setting() {
        $key = session()->get('authenticated')['key'];
        $data = $this->database->getReference('user/' . $key)->getValue();
        $data['key'] = $key;
        return view('session.setting', compact('data'));
    }

    public function simpan_setting(Request $request, $id) {
        $this->validate($request , [
            'username' => 'required',
            'kontak' => 'required',
            'password_lama' => 'nullable',
            'password_baru' => 'nullable',
            'konfirmasi_password' => 'nullable',
        ]);

        $data = $this->database->getReference('user/' . $id)->getValue();
        $password = $data['password'];

        if($request->input('password_lama') != "" and $request->input('password_baru') != "" and $request->input('konfirmasi_password') != "") {
            if($request->input('password_lama') == $data['password']) {
                if($request->input('password_baru') == $request->input('konfirmasi_password')) {
                    $password = $request->input('password_baru');
                }
                else {
                    return back()->withErrors([
                        'message' => 'Password baru dan konfirmasi tidak sama.'
                    ]);
                }
            }
            else {
                return back()->withErrors([
                    'message' => 'Password lama yang anda masukkan salah.'
                ]);
            }
        }

        $this->ref->getChild($id)->set([
            'username' => $request->input('username'),
            'kontak' => $request->input('kontak'),
            'level' => $data['level'],
            'last_login' => $data['last_login'],
            'password' => $password,
        ]);

        return redirect('/setting/form')->with('success', 'Data berhasil diubah');
    } 

    public function logout() {
    	session()->forget('authenticated');
    	return redirect('/');
    }
}
