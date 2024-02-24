<?php

namespace App\Http\Resources;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Chapter;
class videoResource extends JsonResource
{
   function getName()
   {
    // https://drive.google.com/file/d/1iRYc5TrSv14IcEAbMsbFs65xMHsCmK7A/view?usp=sharing
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < 10; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
   }


   /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $chapter = Chapter::find($this->chapter_id);
       // $this->videourl = storage::url($this->videourl);
        return [
            'videoId' => $this->id,
            'videoName' => $this->name,
            'videoDescript' => $this->description,
            'videoSize' => $this->size,
            'thumbUrl' => $this->image,
            'videoUrl' =>$this->url,
            // 'duration' => $this->duration,
            // 'visible' => "wed, JUL 21",
            'author' => User::find($chapter->user_id)->name,
        ];
    }


    public function encrypt( $string, $encrypt=true) {
        $secret_key = 'SuperSecretKey';
        $secret_iv = 'SuperSecretBLOCK';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        if($encrypt) {
            $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        } else {
            $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
        }
        return $output;
    }
}
