<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class ProdiController extends Controller
{
	private $ref;
    private $database;

    public function __construct() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $this->database = $firebase->getDatabase();
        $this->ref = $this->database->getReference('prodi');
    }

    public function show_all() {
        $data = $this->ref->getValue();
        $fakultas_ref = $this->database->getReference('fakultas');

        foreach ($data as $key => $row) {
            $row['key'] = $key;
            $row['nama_fakultas'] = $fakultas_ref->getChild($row['fakultas'])->getValue()['nama'];
            $all_data[] = $row;
        }
        return view('prodi.show_all', compact('all_data'));
    }

    public function form() {
        $ref = $this->database->getReference('fakultas');
        $data = $ref->getValue();

        foreach ($data as $key => $row) {
            $row['key'] = $key;
            $all_fakultas[] = $row;
        }

        return view('prodi.form', compact('all_fakultas'));
    }

    public function simpan(Request $request) {
    	// PERLU UBAH KONFIGURASI DI PHP.INI (POST_SIZE DAN MAX UPLOAD SIZE)
    	$this->validate($request , [
            'nama' => 'required',
            'fakultas' => 'required',
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
        } else {
            $fileNameToStore = 'noimage.jpg';
            $path = "";
        }

        $key = $this->ref->push()->getKey();
        $this->ref->getChild($key)->set([
            'nama' => $request->input('nama'),
            'fakultas' => $request->input('fakultas'),
            'profil' => $request->input('profil'),
            'img_url' => $path,
            'last_edit' => $now
        ]);

        return redirect('/prodi')->with('success', 'Prodi berhasil diterbitkan');
    }

    public function edit($id) {
    	$ref_fakultas = $this->database->getReference('fakultas');
        $data_fakultas = $ref_fakultas->getValue();

        foreach ($data_fakultas as $key => $row) {
            $row['key'] = $key;
            $all_fakultas[] = $row;
        }

        $data = $this->database->getReference('prodi/' . $id)->getValue();
        $data['key'] = $id;
        return view('prodi.form', compact('data', 'all_fakultas'));
    }

    public function simpan_edit(Request $request, $id) {
        $this->validate($request , [
            'nama' => 'required',
            'fakultas' => 'required',
            'profil' => 'required',
            'gambar' => 'nullable',
        ]);

        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());
        $data = $this->database->getReference('prodi/' . $id)->getValue();

        if ($request->hasFile('gambar')) {
            $filenameWithExt = $request->file('gambar')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('gambar')->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            $path = $request->file('gambar')->storeAs('/public/uploadimg', $fileNameToStore);
            Storage::delete($data['img_url']);
        } else {
            $path = $data['img_url'];
        }

        $this->ref->getChild($id)->set([
            'nama' => $request->input('nama'),
            'fakultas' => $request->input('fakultas'),
            'profil' => $request->input('profil'),
            'img_url' => $path,
            'last_edit' => $now
        ]);

        return redirect('/prodi')->with('success', 'Prodi berhasil diubah');
    }
}
