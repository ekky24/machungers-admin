<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Storage;

class LifeAtMaChungController extends Controller
{
	private $ref;
    private $database;

    public function __construct() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $this->database = $firebase->getDatabase();
        $this->ref = $this->database->getReference('lifeatmachung');
        return $this->middleware('usersession');
    }

    public function show_all() {
        $data = $this->ref->getValue();

        foreach ($data as $key => $row) {
            $row['key'] = $key;
            $all_data[] = $row;
        }
        return view('lifeatmachung.show_all', compact('all_data'));
    }

    public function form() {
    	$data = $this->database->getReference('lifeatmachung_upload/')->getValue();

        foreach ($data as $key => $row) {
            $row['key'] = $key;
            $all_data[] = $row;
        }
        return view('lifeatmachung.form', compact('all_data'));
    }

    public function simpan(Request $request) {
    	$this->validate($request , [
            'judul' => 'required',
            'konten' => 'required',
        ]);

        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());

        $key = $this->ref->push()->getKey();
        $this->ref->getChild($key)->set([
            'judul' => $request->input('judul'),
            'konten' => $request->input('konten'),
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
        ]);

        return redirect('/lifeatmachung')->with('success', 'Konten berhasil diterbitkan');
    }

    public function simpan_upload(Request $request) {
    	// PERLU UBAH KONFIGURASI DI PHP.INI (POST_SIZE DAN MAX UPLOAD SIZE)
    	$this->validate($request , [
            'gambar' => 'required',
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

        $ref_upload = $this->database->getReference('lifeatmachung_upload');
        $key = $ref_upload->push()->getKey();
        $ref_upload->getChild($key)->set([
            'img_url' => $path,
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
        ]);

        return redirect('/lifeatmachung/form')->with('success', 'Media berhasil diupload');
    }

    public function edit($id) {
        $data = $this->database->getReference('lifeatmachung/' . $id)->getValue();
        $data['key'] = $id;

        $data_upload = $this->database->getReference('lifeatmachung_upload/')->getValue();

        foreach ($data_upload as $key => $row) {
            $row['key'] = $key;
            $all_data[] = $row;
        }
        return view('lifeatmachung.form', compact('data', 'all_data'));
    }

    public function simpan_edit(Request $request, $id) {
        $this->validate($request , [
            'judul' => 'required',
            'konten' => 'required',
        ]);

        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());
        $data = $this->database->getReference('lifeatmachung/' . $id)->getValue();

        $this->ref->getChild($id)->set([
            'judul' => $request->input('judul'),
            'konten' => $request->input('konten'),
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
        ]);

        return redirect('/lifeatmachung')->with('success', 'Konten berhasil diubah');
    }

    public function delete($id) {
        $this->ref->getChild($id)->remove();
        return redirect('/lifeatmachung')->with('success', 'Konten berhasil dihapus');
    }

    public function delete_upload($id) {
    	$data = $this->database->getReference('lifeatmachung_upload/' . $id)->getValue();
    	Storage::delete($data['img_url']);
        $this->database->getReference('lifeatmachung_upload/' . $id)->remove();
        return redirect('/lifeatmachung/form')->with('success', 'Konten berhasil dihapus');
    }
}
