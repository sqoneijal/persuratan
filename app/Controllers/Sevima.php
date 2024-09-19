<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Sevima extends BaseController
{

   protected $curl;

   public function __construct()
   {
      $this->curl = service('curlrequest', [
         'baseURI' => 'https://api.sevimaplatform.com/siakadcloud/v1/',
         'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-App-Key' => env('SEVIMA_APP_KEY'),
            'X-Secret-Key' => env('SEVIMA_APP_SECRET')
         ]
      ]);
   }

   public function getDetailBiodata(string $slug): object
   {
      try {
         $req = $this->curl->request('GET', 'mahasiswa/' . $slug);
         $body = json_decode($req->getBody(), true);

         return $this->respond(['status' => true, 'data' => $body['attributes']]);
      } catch (\Exception $e) {
         return $this->respond(['status' => false, 'message' => 'Tidak ada data yang ditemukan.']);
      }
   }

   public function getPeriodeAktif(): object
   {
      try {
         $req = $this->curl->request('GET', 'periode');
         $body = json_decode($req->getBody(), true);

         $content = [];
         foreach ($body['data'] as $row) {
            if ($row['attributes']['is_aktif'] === '1') {
               $content = $row['attributes'];
            }
         }

         return $this->respond(['status' => true, 'data' => $content]);
      } catch (\Exception $e) {
         return $this->respond(['status' => false, 'message' => 'Tidak ada data yang ditemukan.']);
      }
   }
}
