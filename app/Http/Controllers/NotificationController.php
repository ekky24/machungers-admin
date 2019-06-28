<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use LaravelFCM\Message\Topics;

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
        return $this->middleware('usersession');
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
            $temp_nim = str_replace(' ', '', $request->input('nim'));
            $nim = explode(",", $temp_nim);
            
            $this->ref->getChild($key)->set([
                'judul' => $request->input('judul'),
                'konten' => $request->input('konten'),
                'nim' => $nim,
                'type' => 'individu',
                'last_edit' => $now,
                'edited_by' => session()->get('authenticated')['key'],
            ]);

            $this->sendIndividual('d5ClGGy1JJY:APA91bHF3soyeiSsf_rlUn21YLgH49-8V1YFb0M3ggP8ivdjNVqYG7OpPmFciurOPYW9eTQXQatYd-Ph5lfmoCykw9NyPjlLEQzonh-bmATeiXWNtPrssC7bcd6qdfeAkerMlM76SjqA', $request->input('judul'), $request->input('konten'));
        }
        elseif($request->has('fakultas')) {
            $this->ref->getChild($key)->set([
                'judul' => $request->input('judul'),
                'konten' => $request->input('konten'),
                'fakultas' => $request->input('fakultas'),
                'type' => 'fakultas',
                'last_edit' => $now,
                'edited_by' => session()->get('authenticated')['key'],
            ]);

            $this->sendTopic('news', $request->input('judul'), $request->input('konten'));
        }
        elseif($request->has('prodi')) {
            $this->ref->getChild($key)->set([
                'judul' => $request->input('judul'),
                'konten' => $request->input('konten'),
                'prodi' => $request->input('prodi'),
                'type' => 'prodi',
                'last_edit' => $now,
                'edited_by' => session()->get('authenticated')['key'],
            ]);

            $this->sendTopic('news', $request->input('judul'), $request->input('konten'));
        }
        else {
            $this->ref->getChild($key)->set([
                'judul' => $request->input('judul'),
                'konten' => $request->input('konten'),
                'type' => 'all',
                'last_edit' => $now,
                'edited_by' => session()->get('authenticated')['key'],
            ]);

            $this->sendTopic('news', $request->input('judul'), $request->input('konten'));
        }

        return redirect('/push')->with('success', 'Notifikasi berhasil diterbitkan');
    }

    public function sendIndividual($token, $judul, $konten) {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($judul);
        $notificationBuilder->setBody($konten)
                            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();


        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        //return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

        //return Array (key : oldToken, value : new token - you must change the token in your database )
        $downstreamResponse->tokensToModify();

        //return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();
    }

    public function sendTopic($group, $judul, $konten) {
        $notificationBuilder = new PayloadNotificationBuilder($judul);
        $notificationBuilder->setBody($konten)
                            ->setSound('default');

        $notification = $notificationBuilder->build();

        $topic = new Topics();
        $topic->topic($group);

        $topicResponse = FCM::sendToTopic($topic, null, $notification, null);

        $topicResponse->isSuccess();
        $topicResponse->shouldRetry();
        $topicResponse->error();
    }
}
