<?php
namespace App\Helpers;

use App\Models\Setting;
use App\DsPushToken;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Crypt;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use RNCryptor\RNCryptor\Decryptor;
use RNCryptor\RNCryptor\Encryptor;
use Illuminate\Support\Str;
use DateTime;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\File;

class Common_function{

    public static function file_upload($file,$path){
        $image = Image::make($file)->orientate();
        $image = $image->stream()->__toString();
        Storage::disk('s3')->put($path, $image, ['x-amz-acl' => 'private']);
        // Storage::disk('public')->put($path, $image,'public');
    }

    public static function thumb_create($file,$path){
        $image = Image::make($file)->resize(335, 335,function ($file) {
            $file->aspectRatio();
            $file->upsize();
        })->encode();
        Storage::disk('s3')->put($path, $image);
        // Storage::disk('public')->put($path, $image,'public');
    }

    public static function delete_file($path){
        // Storage::disk('s3')->delete($path.$image);
        Storage::disk('public')->delete($path);
    }

    public static function store_file($file,$path,$file_name){
        // Storage::disk('public')->putFileAs($path, $file, $file_name);
        Storage::disk('s3')->putFileAs($path, $file, $file_name);
    }


    public static function encrypt($string){
        return Crypt::encryptString($string);
    }

    public static function decrypt($string){
        return Crypt::decryptString($string);
    }

    public static function long_ip($ip){
        return sprintf ("%u", ip2long ($ip));
    }

    //THUMB CREATION OF THE CROPPED IMAGE
    public static function crop_thumb_storage($path,$thumb_path,$cropped_image_file){
        $image = Image::make($cropped_image_file)->resize(335,335,function ($path) {
            $path->aspectRatio();
            $path->upsize();
        })->encode();

        Storage::disk('s3')->put($thumb_path,$image,'');
    }

    public static function thumb_storage($path,$thumb_path){
        $image = Image::make(Storage::disk('s3')->get($path))->resize(335,335,function ($path) {
            $path->aspectRatio();
            $path->upsize();
        })->encode();
        Storage::disk('s3')->put($thumb_path,$image,'');
    }

    // FUNCTION TO GET PROFILE IMAGES ONLY
    public static function check_show_image($image,$folder,$small=false){
        if($image!=''){
            $path = $small==true?config('custom_config.s3_'.$folder.'_thumb'):config('custom_config.s3_'.$folder.'_big');

            return Storage::disk('s3')->url($path.$image);
        }
        else{
            if($folder == "admin"){
                return asset('images/default.png');
            }
            else{
                return asset('images/default.png');
            }
        }
    }

    public static function get_s3_file($path,$image,$expire_time = "+24 hours"){
        if($image!=''){
            $s3 = Storage::disk('s3');
            $client = $s3->getDriver()->getAdapter()->getClient();
            $expiry = $expire_time;

            $command = $client->getCommand('GetObject', [
                'Bucket' => config('custom_config.settings.aws_bucket'),
                'Key'    => $path.$image,
            ]);

            $request = $client->createPresignedRequest($command, $expiry);

            return (string) $request->getUri();
            // return Storage::disk('s3')->url($path.$image);
        }
        else{
            return asset('images/download.png');
        }
    }

    public static function delete_s3($path,$image){
        Storage::disk('s3')->delete($path.$image);
    }

    public static function move_s3_file($server_folder, $local_file, $file_name)
    {
        Storage::disk('s3')->putFileAs($server_folder, new File($local_file), $file_name);
    }

    public static function generateToken($length = 40) {
        $characters = '0123456789';
        $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters)-1;
        $password = '';

        //select some random characters
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[mt_rand(0, $charactersLength)];
        }
        return $password;
    }

    public static function generateTokenNumeric($length = 40) {
        $characters = '0123456789';
        $charactersLength = strlen($characters)-1;
        $password = '';

        //select some random characters
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[mt_rand(0, $charactersLength)];
        }

        return $password;
    }

    public static function str_random($length = 16) {
        return Str::random($length);
    }

    public static function show_date_time($date){
		setlocale(LC_TIME, 'eN_EN'); // substitute your locale if not es_ES
		return  strftime("%d %b, %Y at %I:%M %p", strtotime($date));
    }

    public static function show_pdf_date($date){
		setlocale(LC_TIME, 'eN_EN'); // substitute your locale if not es_ES
		return  strftime("%d %b, %Y", strtotime($date));
    }

    public static function android_push_notification($token,$message,$additional){

        $google_api_key = config('custom_config.fcm_google_api_push_key');

        $url = 'https://fcm.googleapis.com/fcm/send';

        $data = array(
            "title" => config('custom_config.settings.site_name'),
            "message" => $message,

        );

        $dataArray = array_merge($data,$additional);

        $fields = array(
            'registration_ids' => (is_array($token))?$token:array($token),
            'data' => $dataArray,
        );
        //print_r($fields);exit;

        $headers = array(
            'Authorization: key=' . $google_api_key,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);

        if($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return;
    }

    public static function ios_push_notification($token,$message,$type =''){
        $google_api_key = config('custom_config.fcm_google_api_push_key');

        $url = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'registration_ids' => (is_array($token))?$token:array($token),
            "notification" => array(
                "title" => config('custom_config.settings.site_name'),
                "body" => $message,
				"tag" => $type,
				"click_action" => $type,
			),
        );

        $headers = array(
            'Authorization: key=' . $google_api_key,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);

        if($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return;
    }

    public static function date_formatted($datetime) {
        return  strftime("%b %d, %Y", strtotime($datetime));
    }

    // ENCRYPT
    public static function rn_encryption($plainText) {
        $cryptor = new  Encryptor();
        return $cryptor->encrypt($plainText, config('custom_config.rn_cryptor_key'));
    }

    // DECRYPT
    public static function rn_decryption($base64Encrypted) {
        $cryptor = new Decryptor();
        return $cryptor->decrypt($base64Encrypted, config('custom_config.rn_cryptor_key'));
    }
    // MAIL SENDING COMMON FUNCTION
    public static function send_mail($email_data,$users=array()){

        try {
            Mail::send([], [], function ($message) use ($email_data) {
                $message->to(!empty($users)?$users:$email_data['to_email'])
                    ->from($email_data['from_email'], env('APP_NAME'))
                    ->subject(str_replace('[SITE_NAME]', config('custom_config.settings.site_name'), $email_data['subject']))
                    ->setBody($email_data['message'], 'text/html');
            });
        } catch (\Throwable $th) {
            echo "<pre>";print_r($th->getMessage());exit;
		}
        return true;
    }

    public static function get_profile_file($path,$image,$expire_time = ""){
        if($image!=''){
            $s3 = Storage::disk('s3');
            $client = $s3->getDriver()->getAdapter()->getClient();
            $expiry = $expire_time;

            if($expiry == '' || $expiry == 'public'){
                return $client->getObjectUrl(config('custom_config.settings.space_bucket'), $path.$image);
            }
            else{
                $command = $client->getCommand('GetObject', [
                    'Bucket' => config('custom_config.settings.space_bucket'),
                    'Key'    => $path.$image,
                ]);

                $request = $client->createPresignedRequest($command, $expiry);

                return (string) $request->getUri();
            }
            //return asset('storage'.$path.$image);
        }
        else{
            return asset('images/userdefault-1.svg');
        }
    }
}
