<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Storage;

class NewsletterController extends Controller
{
    private $ref;
    private $database;

    public function __construct() {
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $this->database = $firebase->getDatabase();
        $this->ref = $this->database->getReference('newsletter');
        return $this->middleware('usersession');
    }

    public function show_all() {
        $data = $this->ref->getValue();

        foreach ($data as $key => $row) {
            $row['key'] = $key;
            $all_data[] = $row;
        }
        return view('newsletter.show_all', compact('all_data'));
    }

    public function form() {
        return view('newsletter.form');
    }

    public function simpan(Request $request) {
        // PERLU UBAH KONFIGURASI DI PHP.INI (POST_SIZE DAN MAX UPLOAD SIZE)
        $this->validate($request , [
            'pdf' => 'required',
        ]);

        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());
        
        if ($request->hasFile('pdf')) {
            $filenameWithExt = $request->file('pdf')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('pdf')->getClientOriginalExtension();
            $fileNameToStore = time().'.'.$extension;
            $path = $request->file('pdf')->storeAs('/public/newsletter', $fileNameToStore);
            $nama = $fileNameToStore;
        }

        $key = $this->ref->push()->getKey();
        $this->ref->getChild($key)->set([
            'nama' => $nama,
            'path' => substr($path, 7),
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
        ]);

        return redirect('/newsletter')->with('success', 'Newsletter berhasil diterbitkan');
    }

    public function delete($id) {
        $data = $this->database->getReference('newsletter/' . $id)->getValue();
        Storage::delete('public/' . $data['path']);
        $this->ref->getChild($id)->remove();
        return redirect('/newsletter')->with('success', 'Newsletter berhasil dihapus');
    }
}
