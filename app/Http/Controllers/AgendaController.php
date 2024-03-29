<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{
    private $ref;
    private $database;

    public function __construct() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $this->database = $firebase->getDatabase();
        $this->ref = $this->database->getReference('agenda');
        return $this->middleware('usersession');
    }

	public function show_all() {
        $data = $this->ref->getValue();

        foreach ($data as $key => $row) {
            $row['key'] = $key;
            $all_data[] = $row;
        }
        return view('agenda.show_all', compact('all_data'));
    }

    public function form() {
        return view('agenda.form');
    }

    public function simpan(Request $request) {
    	// PERLU UBAH KONFIGURASI DI PHP.INI (POST_SIZE DAN MAX UPLOAD SIZE)
    	$this->validate($request , [
            'judul' => 'required',
            'konten' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
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
            $path = "/public";
        }

        $key = $this->ref->push()->getKey();
        $this->ref->getChild($key)->set([
            'judul' => $request->input('judul'),
            'konten' => $request->input('konten'),
            'tgl_mulai' => $request->input('tgl_mulai'),
            'tgl_selesai' => $request->input('tgl_selesai'),
            'img_url' => substr($path, 7),
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
        ]);

        return redirect('/agenda')->with('success', 'Agenda berhasil diterbitkan');
    }

    public function edit($id) {
        $data = $this->database->getReference('agenda/' . $id)->getValue();
        $data['key'] = $id;
        return view('agenda.form', compact('data'));
    }

    public function simpan_edit(Request $request, $id) {
        $this->validate($request , [
            'judul' => 'required',
            'konten' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
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
            Storage::delete('public/' . $data['img_url']);
        } else {
            $path = "/public" . $data['img_url'];
        }

        $this->ref->getChild($id)->set([
            'judul' => $request->input('judul'),
            'konten' => $request->input('konten'),
            'tgl_mulai' => $request->input('tgl_mulai'),
            'tgl_selesai' => $request->input('tgl_selesai'),
            'img_url' => substr($path, 7),
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
        ]);

        return redirect('/agenda')->with('success', 'Agenda berhasil diubah');
    }

    public function delete($id) {
        $data = $this->database->getReference('agenda/' . $id)->getValue();
        Storage::delete('public/' . $data['img_url']);
        $this->ref->getChild($id)->remove();
        return redirect('/agenda')->with('success', 'Agenda berhasil dihapus');
    }
}
