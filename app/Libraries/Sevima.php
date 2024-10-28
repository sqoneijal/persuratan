<?php

namespace App\Libraries;

class Sevima
{

   public $curl;

   public function __construct()
   {
      $this->curl = service('curlrequest', [
         'baseURI' => env('SEVIMA_PATH_URL'),
         'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-App-Key' => env('SEVIMA_APP_KEY'),
            'X-Secret-Key' => env('SEVIMA_APP_SECRET')
         ]
      ]);
   }

   public function getDetailProgramStudi(string $id_prodi): array
   {
      try {
         $req = $this->curl->request('GET', 'program-studi/' . $id_prodi);
         $body = json_decode($req->getBody(), true);

         return ['status' => true, 'data' => $body['attributes']];
      } catch (\Exception $e) {
         return ['status' => false, 'message' => 'Tidak ada data yang ditemukan.'];
      }
   }

   public function getDetailFakultas(string $id_fakultas): array
   {
      try {
         $req = $this->curl->request('GET', 'fakultas/' . $id_fakultas);
         $body = json_decode($req->getBody(), true);

         return ['status' => true, 'data' => $body['attributes']];
      } catch (\Exception $e) {
         return ['status' => false, 'message' => 'Tidak ada data yang ditemukan.'];
      }
   }
}
