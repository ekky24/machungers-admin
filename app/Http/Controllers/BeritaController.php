<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    private $ref;
    private $database;

    public function __construct() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $this->database = $firebase->getDatabase();
        $this->ref = $this->database->getReference('berita');
        return $this->middleware('usersession');
    }
    
    public function show_all() {
        $berita = $this->ref->getValue();

        foreach ($berita as $key => $row) {
            $row['key'] = $key;
            $all_berita[] = $row;
        }
        return view('berita.show_all', compact('all_berita'));
    }

    public function form() {
        return view('berita.form');
    }

    public function simpan(Request $request) {
    	// PERLU UBAH KONFIGURASI DI PHP.INI (POST_SIZE DAN MAX UPLOAD SIZE)
    	$this->validate($request , [
            'judul' => 'required',
            'konten' => 'required',
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
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $key = $this->ref->push()->getKey();
        $this->ref->getChild($key)->set([
            'judul' => $request->input('judul'),
            'konten' => $request->input('konten'),
            'img_url' => $path,
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
        ]);

        return redirect('/berita')->with('success', 'Berita berhasil diterbitkan');
    }

    public function edit($id) {
        $data = $this->database->getReference('berita/' . $id)->getValue();
        $data['key'] = $id;
        return view('berita.form', compact('data'));
    }

    public function simpan_edit(Request $request, $id) {
        $this->validate($request , [
            'judul' => 'required',
            'konten' => 'required',
            'gambar' => 'required',
        ]);

        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());
        $data = $this->database->getReference('berita/' . $id)->getValue();

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
            'judul' => $request->input('judul'),
            'konten' => $request->input('konten'),
            'img_url' => $path,
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
        ]);

        return redirect('/berita')->with('success', 'Berita berhasil diubah');
    }

    public function delete($id) {
        $data = $this->database->getReference('berita/' . $id)->getValue();
        Storage::delete($data['img_url']);
        $this->ref->getChild($id)->remove();
        return redirect('/berita')->with('success', 'Berita berhasil dihapus');
    }
}
