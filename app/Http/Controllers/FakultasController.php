<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FakultasController extends Controller
{
	public function show_all() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $database = $firebase->getDatabase();
        $ref = $database->getReference('fakultas');
        $data = $ref->getValue();

        foreach ($data as $row) {
            $all_data[] = $row;
        }
        return view('fakultas.show_all', compact('all_data'));
    }

    public function form() {
        return view('fakultas.form');
    }

    public function simpan(Request $request) {
    	// PERLU UBAH KONFIGURASI DI PHP.INI (POST_SIZE DAN MAX UPLOAD SIZE)
    	$this->validate($request , [
            'nama' => 'required',
            'profil' => 'required',
            'gambar' => 'nullable',
        ]);

        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());

        if ($request->hasFile('gambar')) {
            $filenameWithExt = $request->file('gambar')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('gambar')->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            $path = $request->file('gambar')->storeAs('/public/uploadimg', $fileNameToStore);
        }
        else {
        	$path = "";
        }

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $database = $firebase->getDatabase();
        $ref = $database->getReference('fakultas');
        $key = $ref->push()->getKey();
        $ref->getChild($key)->set([
            'nama' => $request->input('nama'),
            'profil' => $request->input('profil'),
            'img_url' => $path,
            'last_edit' => $now
        ]);

        return redirect('/fakultas')->with('success', 'Fakultas berhasil ditambahkan');
    }
}
