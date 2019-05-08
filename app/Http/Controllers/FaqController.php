<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FaqController extends Controller
{
	private $ref;
    private $database;

    public function __construct() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $this->database = $firebase->getDatabase();
        $this->ref = $this->database->getReference('faq');
    }

    public function show_all() {
        $data = $this->ref->getValue();

        foreach ($data as $key => $row) {
            $row['key'] = $key;
            $all_data[] = $row;
        }
        return view('faq.show_all', compact('all_data'));
    }

    public function form() {
        return view('faq.form');
    }

    public function simpan(Request $request) {
    	// PERLU UBAH KONFIGURASI DI PHP.INI (POST_SIZE DAN MAX UPLOAD SIZE)
    	$this->validate($request , [
            'pertanyaan' => 'required',
            'jawaban' => 'required',
        ]);

        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());
        
        $key = $this->ref->push()->getKey();
        $this->ref->getChild($key)->set([
            'pertanyaan' => $request->input('pertanyaan'),
            'jawaban' => $request->input('jawaban'),
            'last_edit' => $now
        ]);

        return redirect('/faq')->with('success', 'FAQ berhasil diterbitkan');
    }

    public function edit($id) {
        $data = $this->database->getReference('faq/' . $id)->getValue();
        $data['key'] = $id;
        return view('faq.form', compact('data'));
    }

    public function simpan_edit(Request $request, $id) {
        $this->validate($request , [
            'pertanyaan' => 'required',
            'jawaban' => 'required',
        ]);

        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());

        $this->ref->getChild($id)->set([
            'pertanyaan' => $request->input('pertanyaan'),
            'jawaban' => $request->input('jawaban'),
            'last_edit' => $now
        ]);

        return redirect('/faq')->with('success', 'FAQ berhasil diubah');
    }

    public function delete($id) {
        $data = $this->database->getReference('faq/' . $id)->getValue();
        $this->ref->getChild($id)->remove();
        return redirect('/faq')->with('success', 'FAQ berhasil dihapus');
    }
}
