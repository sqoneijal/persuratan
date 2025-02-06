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

   public function getStatusIsiKRS(array $post): bool
   {
      $req = $this->curl->request('GET', 'mahasiswa/' . $post['nim'] . '/krs?f-id_periode=' . $post['periode']);
      $body = json_decode($req->getBody(), true);

      $status = false;
      foreach ($body['data'] as $row) {
         $attributes = $row['attributes'];

         if ($attributes['is_krs_disetujui'] === '1') {
            $status = true;
            break;
         }
      }
      return $status;
   }

   public function getDaftarPeriode(): array
   {
      try {
         $req = $this->curl->request('GET', 'periode');
         $body = json_decode($req->getBody(), true);

         $content = [];
         foreach ($body['data'] as $row) {
            if ($row['attributes']['nama_singkat']) {
               array_push($content, $row['attributes']);
            }
         }

         return $content;
      } catch (\Exception $e) {
         return ['status' => false, 'message' => 'Tidak ada data yang ditemukan.'];
      }
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

   public function getBiodataMahasiswa(string $nim): array
   {
      $req = $this->curl->request('GET', 'mahasiswa/' . $nim);
      $body = json_decode($req->getBody(), true);

      return $body['attributes'];
   }
}
