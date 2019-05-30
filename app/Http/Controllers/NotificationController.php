<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class NotificationController extends Controller
{
    private $ref;
    private $database;

    public function __construct() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $this->database = $firebase->getDatabase();
        $this->ref = $this->database->getReference('push_notification');
    }

    public function show_all() {
        $data = $this->ref->getValue();

        foreach ($data as $key => $row) {
            $row['key'] = $key;
            $all_data[] = $row;
        }
        return view('notification.show_all', compact('all_data'));
    }

    public function form_push_all() {
        return view('notification.form_push_all');
    }

    public function form_push_fakultas() {
        $ref = $this->database->getReference('fakultas');
        $data = $ref->getValue();

        foreach ($data as $key => $row) {
            $row['key'] = $key;
            $all_fakultas[] = $row;
        }
        return view('notification.form_push_fakultas', compact('all_fakultas'));
    }

    public function form_push_prodi() {
        $ref = $this->database->getReference('prodi');
        $data = $ref->getValue();

        foreach ($data as $key => $row) {
            $row['key'] = $key;
            $all_prodi[] = $row;
        }
        return view('notification.form_push_prodi', compact('all_prodi'));
    }

    public function form_push_individu() {
        return view('notification.form_push_individu');
    }

    public function simpan(Request $request) {
        // PERLU UBAH KONFIGURASI DI PHP.INI (POST_SIZE DAN MAX UPLOAD SIZE)
        $this->validate($request , [
            'judul' => 'required',
            'konten' => 'required',
            'nim' => 'nullable',
            'fakultas' => 'nullable',
            'prodi' => 'nullable',
        ]);

        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());
        $key = $this->ref->push()->getKey();
        
        if ($request->has('nim')) {
            $this->ref->getChild($key)->set([
                'judul' => $request->input('judul'),
                'konten' => $request->input('konten'),
                'nim' => $request->input('nim'),
                'type' => 'individu',
                'last_edit' => $now
            ]);
        }
        elseif($request->has('fakultas')) {
            $this->ref->getChild($key)->set([
                'judul' => $request->input('judul'),
                'konten' => $request->input('konten'),
                'fakultas' => $request->input('fakultas'),
                'type' => 'fakultas',
                'last_edit' => $now
            ]);
        }
        elseif($request->has('prodi')) {
            $this->ref->getChild($key)->set([
                'judul' => $request->input('judul'),
                'konten' => $request->input('konten'),
                'prodi' => $request->input('prodi'),
                'type' => 'prodi',
                'last_edit' => $now
            ]);
        }
        else {
            $this->ref->getChild($key)->set([
                'judul' => $request->input('judul'),
                'konten' => $request->input('konten'),
                'type' => 'all',
                'last_edit' => $now
            ]);
        }

        return redirect('/push')->with('success', 'Notifikasi berhasil diterbitkan');
    }
}
