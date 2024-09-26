<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Sevima extends BaseController
{

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

   public function getStatusPembayaranSPP(): object
   {
      try {
         $req = $this->curl->request('GET', 'mahasiswa/' . $this->post['nim'] . '/invoice');
         $body = json_decode($req->getBody(), true);

         $status = false;
         foreach ($body['data'] as $row) {
            if ($row['attributes']['is_lunas'] === '1' && $row['attributes']['id_periode'] === $this->post['periode']) {
               $status = true;
            }
         }

         return $this->respond(['status' => true, 'data' => $status]);
      } catch (\Exception $e) {
         return $this->respond(['status' => false, 'message' => 'Tidak ada data yang ditemukan.']);
      }
   }
}
