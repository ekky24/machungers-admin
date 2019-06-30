<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Storage;

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
        $prodi_ref = $this->database->getReference('prodi');

        foreach ($data as $key => $row) {
            $prodi = $prodi_ref->getChild($row['prodi'])->getValue();
            $row['key'] = $key;
            $row['nama_prodi'] = $prodi['nama'];
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
            $path = "/public";
        }

        $key = $this->ref->push()->getKey();
        $this->ref->getChild($key)->set([
            'nim' => $request->input('nim'),
            'nama' => $request->input('nama'),
            'password' => '25d55ad283aa400af464c76d713c07ad',
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tgl_lahir' => $request->input('tgl_lahir'),
            'prodi' => $request->input('prodi'),
            'img_url' => substr($path, 7),
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
            Storage::delete('public/' . $data['img_url']);
        } else {
            $path = "/public" . $data['img_url'];
        }

        $this->ref->getChild($id)->set([
            'nim' => $request->input('nim'),
            'nama' => $request->input('nama'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tgl_lahir' => $request->input('tgl_lahir'),
            'prodi' => $request->input('prodi'),
            'img_url' => substr($path, 7),
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
        ]);

        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil diubah');
    }

    public function delete($id) {
        $data = $this->database->getReference('mahasiswa/' . $id)->getValue();
        Storage::delete('public/' . $data['img_url']);
        $this->ref->getChild($id)->remove();
        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil dihapus');
    }

    public function ajax_fakultas() {
        $data = $this->ref->getValue();
        $prodi_arr = [];
        $prodi_arr_temp = [];
        $sama = false;

        // PENGHITUNGAN MAHASISWA BERDASARKAN PRODI
        foreach ($data as $key => $row) {
            $data = $this->database->getReference('prodi/' . $row['prodi'])->getValue();
            array_push($prodi_arr_temp, $data);

            if(count($prodi_arr) > 0) {
                foreach ($prodi_arr as $i => $row_prodi) {
                    if($row['prodi'] == $row_prodi[0]) {
                        $sama = true;
                        $prodi_arr[$i] = [$row['prodi'], $data['nama'], $row_prodi[2] + 1];
                        break;
                    }
                }

                if(!$sama) {
                    array_push($prodi_arr, [$row['prodi'], $data['nama'], 1]);
                }
                $sama = false;
            }
            else {
                array_push($prodi_arr, [$row['prodi'], $data['nama'], 1]);
            }
        }

        $fakultas_arr = [];
        $sama = false;
        // PENGHITUNGAN MAHASISWA BERDASARKAN FAKULTAS
        foreach ($prodi_arr as $i => $row_prodi) {
            $data = $prodi_arr_temp[$i];
            $data_fakultas = $this->database->getReference('fakultas/' . $data['fakultas'])->getValue();

            if(count($fakultas_arr) > 0) {
                foreach ($fakultas_arr as $i => $row_fakultas) {
                    if($data['fakultas'] == $row_fakultas[0]) {
                        $sama = true;
                        $fakultas_arr[$i] = [$data['fakultas'], $data_fakultas['nama'], $row_fakultas[2] + $row_prodi[2]];
                        break;
                    }
                }

                if(!$sama) {
                    array_push($fakultas_arr, [$data['fakultas'], $data_fakultas['nama'], $row_prodi[2]]);
                }
                $sama = false;
            }
            else {
                array_push($fakultas_arr, [$data['fakultas'], $data_fakultas['nama'], $row_prodi[2]]);
            }
        }

        $final_arr['prodi'] = $prodi_arr;
        $final_arr['fakultas'] = $fakultas_arr;
        return json_encode($final_arr);
    }
}
