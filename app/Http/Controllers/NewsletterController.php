<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

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
        $this->ref = $this->database->getReference('mahasiswa');
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
}
