<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Absen extends BaseController
{
   public function index()
   {
      $client = service('curlrequest');

      // URL API yang dituju
      $url = 'https://kong.ar-raniry.ac.id/kehadiran/v5/kehadiran';

      // Parameter idPegawai
      $params = [
         'idPegawai' => '199108172023211039',
      ];

      // Header untuk API Key
      $headers = [
         'apikey' => 'H7MPhqE5yZN5nwZrSZSApxIVhvgLjGNe',
      ];

      try {
         // Kirim POST request dengan parameter dan header
         $response = $client->post($url, [
            'headers' => $headers,
            'query'   => $params, // Mengirimkan idPegawai sebagai query string
         ]);

         // Menampilkan respons
         $responseBody = $response->getBody();
         return $this->response->setJSON([
            'status' => 'success',
            'data'   => json_decode($responseBody, true)
         ]);
      } catch (\Exception $e) {
         // Jika ada error, tampilkan pesan error
         return $this->response->setJSON([
            'status'  => 'error',
            'message' => $e->getMessage(),
         ]);
      }
   }
}
