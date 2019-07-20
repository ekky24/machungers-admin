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
        $data_prodi = $prodi_ref->getValue();

        foreach ($data as $key => $row) {
            foreach ($data_prodi as $key_prodi => $row_prodi) {
                if ($row['prodi'] == $key_prodi) {
                    $row['nama_prodi'] = $row_prodi['nama'];
                    break;
                }
            }
            $row['key'] = $key;
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
            'fcm_token' => '',
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tgl_lahir' => $request->input('tgl_lahir'),
            'prodi' => $request->input('prodi'),
            'img_url' => substr($path, 7),
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
            'login' => false,
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
        $data = $this->database->getReference('mahasiswa/' . $id)->getValue();

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
            'password' => $data['password'],
            'fcm_token' => $data['fcm_token'],
            'tgl_lahir' => $request->input('tgl_lahir'),
            'prodi' => $request->input('prodi'),
            'img_url' => substr($path, 7),
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
            'login' => $data['login'],
        ]);

        return redirect('/mahasiswa')->with('success', 'Mahasiswa berhasil diubah');
    }

    public function resetpass(Request $request, $id) {
        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());
        $data = $this->database->getReference('mahasiswa/' . $id)->getValue();

        $this->ref->getChild($id)->set([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'tempat_lahir' => $data['tempat_lahir'],
            'password' => '25d55ad283aa400af464c76d713c07ad',
            'fcm_token' => $data['fcm_token'],
            'tgl_lahir' => $data['tgl_lahir'],
            'prodi' => $data['prodi'],
            'img_url' => $data['img_url'],
            'last_edit' => $now,
            'edited_by' => session()->get('authenticated')['key'],
            'login' => $data['login'],
        ]);

        return redirect('/mahasiswa')->with('success', 'Password berhasil direset');
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

        // PENGHITUNGAN MAHASISWA BERDASARKAN PRODI
        $all_prodi = $this->database->getReference('prodi')->getValue();
        foreach ($all_prodi as $key_prodi => $row_prodi) {
            $count = 0;
            foreach ($data as $row_mahasiswa) {
                if($key_prodi == $row_mahasiswa['prodi']) {
                    $count += 1;
                }
            }

            if($row_prodi['nama'] == "Teknik Informatika") {
                $new_name = "TIF";
            }
            elseif($row_prodi['nama'] == "Sastra Inggris") {
                $new_name = "Inggris";
            }
            elseif($row_prodi['nama'] == "Teknik Industri") {
                $new_name = "Industri";
            }
            elseif($row_prodi['nama'] == "Pendidikan Bahasa Mandarin") {
                $new_name = "Mandarin";
            }
            else {
                $prodi_word = explode(' ', $row_prodi['nama']);
                if(count($prodi_word) > 1) {
                    $new_name = "";
                    foreach ($prodi_word as $row) {
                        $new_name = $new_name . strtoupper(substr($row, 0, 1));
                    }
                }
                else {
                    $new_name = $row_prodi['nama'];
                }
            }

            array_push($prodi_arr, [$key_prodi, $new_name, $count, $row_prodi['fakultas']]);
        }

        // PENGHITUNGAN MAHASISWA BERDASARKAN FAKULTAS
        $fakultas_arr = [];
        $all_fakultas = $this->database->getReference('fakultas')->getValue();
        foreach ($all_fakultas as $key_fakultas => $row_fakultas) {
            $count = 0;
            foreach ($prodi_arr as $row_prodi) {
                if($key_fakultas == $row_prodi[3]) {
                    $count += $row_prodi[2];
                }
            }
            array_push($fakultas_arr, [$key_fakultas, $row_fakultas['nama'], $count]);
        }

        $final_arr['prodi'] = $prodi_arr;
        $final_arr['fakultas'] = $fakultas_arr;
        return json_encode($final_arr);
    }

    public function form_csv() {
        return view('mahasiswa.form_csv');
    }

    public function simpan_csv(Request $request) {
        $file = public_path('csv/mahasiswa.csv');
        $mahasiswa_arr = $this->csvToArray($file);
        $prodi_arr = $this->database->getReference('prodi')->getValue();
        $mahasiswa_real_ref = $this->database->getReference('mahasiswa');
        date_default_timezone_set('Asia/Jakarta');
        $now = date('d/m/Y h:i:s a', time());
        $handle = fopen($file, 'w');
        $header = false;

        for ($i = 0; $i < count($mahasiswa_arr); $i++) {
            if ($i < 10) {
                foreach ($prodi_arr as $key_prodi => $row) {
                    if($mahasiswa_arr[$i]['prodi'] == $row['nama']) {
                        $key = $mahasiswa_real_ref->push()->getKey();
                        $mahasiswa_real_ref->getChild($key)->set([
                            'nim' => $mahasiswa_arr[$i]['nim'],
                            'nama' => $mahasiswa_arr[$i]['nama'],
                            'password' => '25d55ad283aa400af464c76d713c07ad',
                            'fcm_token' => '',
                            'tempat_lahir' => '',
                            'tgl_lahir' => $mahasiswa_arr[$i]['tgl_lahir'],
                            'prodi' => $key_prodi,
                            'img_url' => '',
                            'last_edit' => $now,
                            'edited_by' => session()->get('authenticated')['key'],
                            'login' => false,
                        ]);
                    }
                }   
            }
            else {
                if (!$header) {
                    fputcsv($handle, ['nama','tgl_lahir','nim','tahun_masuk','prodi']);
                    $header = true;
                }
                fputcsv($handle, $mahasiswa_arr[$i]);
            }
        }
        fclose($handle);
    }

    function csvToArray($filename = '', $delimiter = ',') {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}
