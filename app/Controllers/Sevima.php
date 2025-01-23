<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Sevima extends BaseController
{

   public function transkripAkhir(): object
   {
      try {
         $req = $this->curl->request('GET', 'mahasiswa/' . $this->post['nim'] . '/transkrip');
         $body = json_decode($req->getBody(), true);

         $attributes = [];
         foreach ($body['data'] as $row) {
            $attributes[] = $row['attributes'];
         }

         return $this->respond(['status' => true, 'data' => $attributes]);
      } catch (\Exception $e) {
         return ['status' => false, 'message' => $e->getMessage()];
      }
   }

   public function getKhs(): object
   {
      try {
         $req = $this->curl->request('GET', 'mahasiswa/' . $this->post['nim'] . '/khs?f-is_nilai_akhir=1');
         $body = json_decode($req->getBody(), true);

         $periode = [];
         $attributes = [];
         foreach ($body['data'] as $row) {
            $att = $row['attributes'];

            $periode[] = $att['id_periode'];
            $attributes[] = $att;
         }

         $daftar_matkul = [];
         foreach ($attributes as $row) {
            $daftar_matkul[$row['id_periode']][] = $row;
         }

         $uniqueArray = array_values(array_unique($periode));
         sort($uniqueArray);

         $content = [
            'periode' => $uniqueArray,
            'daftar_matkul' => $daftar_matkul
         ];

         return $this->respond(['status' => true, 'data' => $content]);
      } catch (\Exception $e) {
         return ['status' => false, 'message' => $e->getMessage()];
      }
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

   public function getStatusPembayaranSPP(): object
   {
      try {
         $req = $this->curl->request('GET', 'mahasiswa/' . $this->post['nim'] . '/perwalian?f-is_krs_terisi=1');
         $body = json_decode($req->getBody(), true);

         $status = false;
         foreach ($body['data'] as $row) {
            if ($row['attributes']['id_periode'] === $this->post['periode']) {
               $status = true;
            }
         }

         return $this->respond(['status' => true, 'data' => $status]);
      } catch (\Exception $e) {
         return $this->respond(['status' => false, 'message' => 'Tidak ada data yang ditemukan.']);
      }
   }
}
