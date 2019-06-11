<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class MahasiswaController extends Controller
{
	private $ref;
    private $database;

    public function __construct() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $this->database = $firebase->getDatabase();
        $this->ref = $this->database->getReference('mahasiswa');
        return $this->middleware('usersession');
    }

    public function show_all() {
        $data = $this->ref->getValue();
        $fakultas_ref = $this->database->getReference('fakultas');

        foreach ($data as $key => $row) {
            $row['key'] = $key;
            $row['nama_fakultas'] = $fakultas_ref->getChild($row['fakultas'])->getValue()['nama'];
            $all_data[] = $row;
        }
        return view('mahasiswa.show_all', compact('all_data'));
    }

    public function form() {
    	$ref_fakultas = $this->database->getReference('fakultas');
    	$ref_prodi = $this->database->getReference('prodi');
        $data_fakultas = $ref_fakultas->getValue();
        $data_prodi = $ref_prodi->getValue();

        foreach ($data_fakultas as $key => $row) {
            $row['key'] = $key;
            $all_fakultas[] = $row;
        }

        foreach ($data_prodi as $key => $row) {
            $row['key'] = $key;
            $all_prodi[] = $row;
        }
        return view('mahasiswa.form', compact('all_fakultas', 'all_prodi'));
    }

    public function simpan(Request $request) {
    	// PERLU UBAH KONFIGURASI DI PHP.INI (POST_SIZE DAN MAX UPLOAD SIZE)
    	$this->validate($request , [
            'nim' => 'required|numeric',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'fakultas' => 'required',
            'prodi' => 'required',
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
            'nim' => $request->input('nim'),
            'nama' => $request->input('nama'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tgl_lahir' => $request->input('tgl_lahir'),
            'fakultas' => $request->input('fakultas'),
            'prodi' => $request->input('prodi'),
            'img_url' => $path,
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
        ]);

        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function edit($id) {
    	$ref_fakultas = $this->database->getReference('fakultas');
    	$ref_prodi = $this->database->getReference('prodi');
        $data_fakultas = $ref_fakultas->getValue();
        $data_prodi = $ref_prodi->getValue();

        foreach ($data_fakultas as $key => $row) {
            $row['key'] = $key;
            $all_fakultas[] = $row;
        }

        foreach ($data_prodi as $key => $row) {
            $row['key'] = $key;
            $all_prodi[] = $row;
        }

        $data = $this->database->getReference('mahasiswa/' . $id)->getValue();
        $data['key'] = $id;
        return view('mahasiswa.form', compact('data', 'all_fakultas', 'all_prodi'));
    }

    public function simpan_edit(Request $request, $id) {
        $this->validate($request , [
            'nim' => 'required|numeric',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'fakultas' => 'required',
            'prodi' => 'required',
            'gambar' => 'nullable',
        ]);

        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());
        $data = $this->database->getReference('agenda/' . $id)->getValue();

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
            'nim' => $request->input('nim'),
            'nama' => $request->input('nama'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tgl_lahir' => $request->input('tgl_lahir'),
            'fakultas' => $request->input('fakultas'),
            'prodi' => $request->input('prodi'),
            'img_url' => $path,
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
        ]);

        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil diubah');
    }

    public function delete($id) {
        $data = $this->database->getReference('mahasiswa/' . $id)->getValue();
        Storage::delete($data['img_url']);
        $this->ref->getChild($id)->remove();
        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil dihapus');
    }
}
