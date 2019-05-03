<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class AgendaController extends Controller
{
	public function show_all() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $database = $firebase->getDatabase();
        $ref = $database->getReference('agenda');
        $data = $ref->getValue();

        foreach ($data as $row) {
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
        $tgl_mulai = date('d/m/Y', time());
        $tgl_selesai = date('d/m/Y', time());

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

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $database = $firebase->getDatabase();
        $ref = $database->getReference('agenda');
        $key = $ref->push()->getKey();
        $ref->getChild($key)->set([
            'judul' => $request->input('judul'),
            'konten' => $request->input('konten'),
            'tgl_mulai' => $request->input('tgl_mulai'),
            'tgl_selesai' => $request->input('tgl_selesai'),
            'img_url' => $path,
            'last_edit' => $now
        ]);

        return redirect('/agenda')->with('success', 'Berita berhasil diterbitkan');
    }
}
