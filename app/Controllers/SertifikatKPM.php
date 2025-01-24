<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use App\Controllers\BaseController;
use App\Models\SertifikatKPM as Model;

class SertifikatKPM extends BaseController
{
   public function getData(): object
   {
      $model = new Model();
      $content = $model->getData($this->post['nim']);
      return $this->respond($content);
   }

   public function getDetailBiodata(string $slug): array
   {
      try {
         $req = $this->curl->request('GET', 'mahasiswa/' . $slug);
         $body = json_decode($req->getBody(), true);

         return ['status' => true, 'data' => $body['attributes']];
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

   public function cetak(int $id): void
   {
      $model = new Model();
      $row = $model->getDetailCetak($id);

      if ($row) {
         $sevima = $this->getDetailBiodata($row['nim']);
         $sevima_prodi = $this->getDetailProgramStudi($sevima['data']['id_program_studi']);
         $sevima_fakultas = $this->getDetailFakultas($sevima_prodi['data']['id_fakultas']);

         $logo_pusaka = ROOTPATH . 'public/logo_pusaka.png';
         $type = pathinfo($logo_pusaka, PATHINFO_EXTENSION);
         $data = file_get_contents($logo_pusaka);
         $base64_logo_pusaka = 'data:image/' . $type . ';base64,' . base64_encode($data);

         $logo_unggul = ROOTPATH . 'public/logo_unggul.png';
         $type = pathinfo($logo_unggul, PATHINFO_EXTENSION);
         $data = file_get_contents($logo_unggul);
         $base64_logo_unggul = 'data:image/' . $type . ';base64,' . base64_encode($data);

         $logo_blu = ROOTPATH . 'public/logo_blu.png';
         $type = pathinfo($logo_blu, PATHINFO_EXTENSION);
         $data = file_get_contents($logo_blu);
         $base64_logo_blu = 'data:image/' . $type . ';base64,' . base64_encode($data);

         $logo_uin = ROOTPATH . 'public/logo_uin.png';
         $type = pathinfo($logo_uin, PATHINFO_EXTENSION);
         $data = file_get_contents($logo_uin);
         $base64_logo_uin = 'data:image/' . $type . ';base64,' . base64_encode($data);

         $dompdf = new Dompdf();
         $dompdf->setPaper('A4', 'landscape');
         $dompdf->loadHtml('<!DOCTYPE html>
               <html lang="en">
               <head>
                  <meta charset="UTF-8" />
                  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                  <title>Setifikat KPM</title>
               </head>
               <body>
                  <table style="width: 100%; border-collapse: collapse;">
                     <tr>
                        <td style="text-align: center; vertical-align: middle" rowspan="2"><img src="' . $base64_logo_uin . '" style="width: 100px; height: 110px" alt="logo uin" /></td>
                        <td style="text-align: center; vertical-align: middle; font-size: 55px; font-weight: bold;">SERTIFIKAT</td>
                        <td style="text-align: center; vertical-align: middle" rowspan="2">
                           <img src="' . $base64_logo_blu . '" style="width: 50px; height: 50px" alt="logo uin" />
                           <img src="' . $base64_logo_unggul . '" style="width: 50px; height: 50px" alt="logo uin" />
                           <img src="' . $base64_logo_pusaka . '" style="width: 35px; height: 50px" alt="logo uin" />
                        </td>
                     </tr>
                     <tr>
                        <td style="text-align: center; vertical-align: middle">
                           <div style="background-color: #4fb4e7; font-weight: bold; padding: 10px; color: #ffffff;">Nomor : ' . $row['nomor_sertifikat'] . '</div>
                        </td>
                     </tr>
                  </table>
                  <div style="font-size: 18px; padding-top: 20px; padding-left: 50px; padding-right: 50px;">Dalam rangka penyelenggaraan Tri Dharma Perguruan Tinggi,<br/>
                  Rektor Universitas Islam Negeri Ar-Raniry Banda Aceh, menerangkan bahwa yang bernama;</div>
                  <div style="padding-top: 20px; font-weight: bold; font-size: 23px; text-align: center;">' . $sevima['data']['nama'] . '</div>
                  <div style="font-size: 18px; text-align: center;">' . $sevima['data']['nim'] . ' | ' . $sevima_fakultas['data']['nama'] . '</div>
                  <div style="font-size: 18px; text-align: justify; padding-top: 20px; padding-left: 50px; padding-right: 50px;">' . $row['keterangan'] . '</div>
                  <div style="font-size: 23px; text-align: center; padding-top: 20px; font-weight: bold;">( ' . $row['nilai'] . ' )</div>
                  <div style="text-align: justify; padding-top: 20px; font-size: 18px; padding-left: 50px; padding-right: 50px;">Dan kepada yang bersangkutan diberikan sertifikat ini untuk dapat dipergunakan sebagaimana mestinya.</div>
                  <table style="width: 100%; border-collapse: collapse; padding-top: 20px;">
                     <tr>
                        <td style="text-align: center; vertical-align: middle">pas photo<br/>3x4</td>
                        <td style="width: 50%; font-size: 18px; padding-left: 60px;">
                           Banda Aceh, ' . date('d F Y', strtotime($row['tanggal_sertifikat'])) . '<br/>
                           Rektor,<br/>
                           <br/>
                           <br/>
                           <br/>
                           <br/>
                           <br/>
                           Prof. Dr. H. Mujiburrahman, M.Ag.<br/>
                           NIP: 197109082001121001
                        </td>
                     </tr>
                  </table>
               </body>
               </html>');

         $dompdf->render();
         $dompdf->stream("sertifikat_kpm.pdf", array("Attachment" => false));
      }
   }
}
