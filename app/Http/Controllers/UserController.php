<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Storage;
use Hash;

class UserController extends Controller
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
        return $this->middleware('usersession');
    }

    public function show_all() {
        $data = $this->ref->getValue();

        foreach ($data as $key => $row) {
        	if($row['level'] != 'super') {
        		$row['key'] = $key;
            	$all_data[] = $row;
        	}
        }
        return view('user.show_all', compact('all_data'));
    }

    public function form() {
        return view('user.form');
    }

    public function simpan(Request $request) {
    	// PERLU UBAH KONFIGURASI DI PHP.INI (POST_SIZE DAN MAX UPLOAD SIZE)
    	$this->validate($request , [
            'username' => 'required',
            'nama_departemen' => 'required',
            'kontak' => 'nullable',
            'password' => 'required',
            'konfirmasi_password' => 'required',
            'level' => 'required',
        ]);

        if($request->input('password') != $request->input('konfirmasi_password')) {
            return back()->withErrors([
                'message' => 'Password baru dan konfirmasi tidak sama.'
            ]);
        }

        if ($request->input('kontak') != "") {
            $kontak = $request->input('kontak');
        }
        else {
            $kontak = "";
        }

        $key = $this->ref->push()->getKey();
        $this->ref->getChild($key)->set([
            'username' => $request->input('username'),
            'nama_departemen' => $request->input('nama_departemen'),
            'kontak' => $kontak,
            'password' => Hash::make($request->input('password')),
            'level' => $request->input('level'),
            'last_login' => '00/00/0000',
        ]);

        return redirect('/user')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id) {
        $data = $this->database->getReference('user/' . $id)->getValue();
        $data['key'] = $id;
        return view('user.form', compact('data'));
    }

    public function simpan_edit(Request $request, $id) {
        $this->validate($request , [
            'username' => 'required',
            'nama_departemen' => 'required',
            'kontak' => 'nullable',
            'password' => 'nullable',
            'konfirmasi_password' => 'nullable',
            'level' => 'required',
        ]);

        $data = $this->database->getReference('user/' . $id)->getValue();
        $password = $data['password'];

        if($request->input('password') != "" and $request->input('konfirmasi_password') != "") {
            if($request->input('password') == $request->input('konfirmasi_password')) {
                $password = $request->input('password');
            }
            else {
                return back()->withErrors([
                    'message' => 'Password baru dan konfirmasi tidak sama.'
                ]);
            }
        }

        if ($request->input('kontak') != "") {
            $kontak = $request->input('kontak');
        }
        else {
            $kontak = "";
        }

        $this->ref->getChild($id)->set([
            'username' => $request->input('username'),
            'nama_departemen' => $request->input('nama_departemen'),
            'kontak' => $kontak,
            'level' => $request->input('level'),
            'last_login' => $data['last_login'],
            'password' => Hash::make($password),
        ]);

        return redirect('/user')->with('success', 'User berhasil diubah');
    }

    public function delete($id) {
        $data = $this->database->getReference('user/' . $id)->getValue();
        $this->ref->getChild($id)->remove();
        return redirect('/user')->with('success', 'User berhasil dihapus');
    }
}
