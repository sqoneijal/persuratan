<?php

namespace App\Controllers\Akademik;

use App\Controllers\BaseController;
use App\Validation\Akademik\Magang as Validate;
use App\Models\Akademik\Magang as Model;
use Dompdf\Dompdf;
use chillerlan\QRCode\QRCode;

class Magang extends BaseController
{

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

   public function getDetailJurusan(string $id_jurusan): array
   {
      try {
         $req = $this->curl->request('GET', 'jurusan/' . $id_jurusan);
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

   public function cetak(int $id): void
   {
      $model = new Model();
      $row = $model->getDetailCetak($id);

      $sevima = $this->getDetailBiodata($row['nim']);

      if ($sevima['status']) {
         $sevima_prodi = $this->getDetailProgramStudi($sevima['data']['id_program_studi']);
         $sevima_jurusan = $this->getDetailJurusan($sevima_prodi['data']['id_unit_parent']);
         $sevima_fakultas = $this->getDetailFakultas($sevima_jurusan['data']['id_unit_parent']);

         $logo_uin = ROOTPATH . 'public/logo_uin.png';
         $type = pathinfo($logo_uin, PATHINFO_EXTENSION);
         $data = file_get_contents($logo_uin);
         $base64_logo_uin = 'data:image/' . $type . ';base64,' . base64_encode($data);

         $linkBerkas = 'https://mael.api.ar-raniry.ac.id/akademik/magang/cetak/' . $row['id'];
         $qrCode = (new QRCode)->render($linkBerkas);

         $dompdf = new Dompdf();
         $dompdf->setPaper('A4', 'potrait');
         $dompdf->loadHtml('<!DOCTYPE html>
               <html lang="en">
               <head>
                  <meta charset="UTF-8" />
                  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                  <title>Surat Magang</title>
               </head>
               <body>
                  <table style="width: 100%;">
                     <tbody>
                        <tr>
                           <td style="text-align: center; vertical-align: middle"><img src="' . $base64_logo_uin . '" style="width: 100px; height: 110px" alt="logo uin" /></td>
                           <td style="text-align: center; vertical-align: middle;">
                              <span style="margin-left: -50px; font-weight: bold;">KEMENTERIAN AGAMA REPUBLIK INDONESIA</span><br />
                              <span style="margin-left: -50px; font-weight: bold;">UNIVERSITAS ISLAM NEGERI AR-RANIRY BANDA ACEH</span><br />
                              <span style="margin-left: -50px; font-weight: bold;">' . strtoupper($sevima_fakultas['data']['nama']) . '</span><br />
                              <span style="font-size: 12px; margin-left: -50px">Jl. Syeikh Abdur Rauf Kopelma Darussalam Banda Aceh Telp/Fax. : 0651-752921</span>
                           </td>
                        </tr>
                     </tbody>
                  </table>
                  <table style="width: 100%;">
                     <tbody>
                        <tr>
                           <td style="width: 10%;">Nomor</td>
                           <td style="width: 1%;">:</td>
                           <td>' . $row['no_surat'] . '</td>
                        </tr>
                        <tr>
                           <td>Lampiran</td>
                           <td>:</td>
                           <td>-</td>
                        </tr>
                        <tr>
                           <td>Hal</td>
                           <td>:</td>
                           <td style="font-style: italic;">Permohonan Izin Magang Kerja</td>
                        </tr>
                     </tbody>
                  </table>
                  <br/>
                  <table style="width: 100%;">
                     <tbody>
                        <tr>
                           <td>Kepada Yth,</td>
                        </tr>
                        <tr>
                           <td>&nbsp;&nbsp;&nbsp;' . $row['tujuan_surat'] . '</td>
                        </tr>
                        <tr>
                           <td>di</td>
                        </tr>
                        <tr>
                           <td>&nbsp;&nbsp;&nbsp;' . $row['lokasi_magang'] . '</td>
                        </tr>
                     </tbody>
                  </table>
                  <br/>
                  <table style="width: 100%;">
                     <tbody>
                        <tr>
                           <td>Assalamualaikum Warahmatullah Wabarakatuh</td>
                        </tr>
                        <tr>
                           <td style="text-align: justify;">Menindaklanjuti permohonan izin magang dari mahasiswa, Program Studi ' . ucwords($sevima_prodi['data']['nama']) . ' Islam UIN Ar-Raniry Banda Aceh :</td>
                        </tr>
                     </tbody>
                  </table>
                  <br/>
                  <table style="width: 100%;">
                     <tbody>
                        <tr>
                           <td style="width: 15%;">Nama</td>
                           <td style="width: 1%;">:</td>
                           <td>' . strtoupper($sevima['data']['nama']) . '</td>
                        </tr>
                        <tr>
                           <td>NIM</td>
                           <td>:</td>
                           <td>' . $sevima['data']['nim'] . '</td>
                        </tr>
                        <tr>
                           <td>Nomor Kontak</td>
                           <td>:</td>
                           <td>' . $row['no_kontak'] . '</td>
                        </tr>
                        <tr>
                           <td>Alamat</td>
                           <td>:</td>
                           <td>' . $row['alamat'] . '</td>
                        </tr>
                     </tbody>
                  </table>
                  <br/>
                  <table style="width: 100%;">
                     <tbody>
                        <tr>
                           <td style="text-align: justify;">Dengan ini kami mohon Bapak/Ibu berkenan memberikan kesempatan kepada mahasiswa tersebut untuk melakukan magang kerja selama minimal 30 hari kerja di Kantor ' . $row['tujuan_surat'] . '</td>
                        </tr>
                        <tr>
                           <td style="text-align: justify;">Perlu kami informasikan bahwa kegiatan magang ini dalam rangka mendukung Program Pemerintah dalam bentuk Program Kampus Merdeka dan Merdeka Belajar.</td>
                        </tr>
                        <tr>
                           <td style="text-align: justify;">Demikianlah permohonan ini kami sampaikan, atas perkenannya kami ucapkan terimakasih.</td>
                        </tr>
                     </tbody>
                  </table>
                  <table style="width: 100%;">
                     <tbody>
                        <tr>
                           <td style="vertical-align: bottom;"></td>
                           <td style="width: 50%;">
                              Banda Aceh, ' . tanggal_indo(date('Y-m-d', strtotime($row['modified']))) . '<br/>
                              An. Dekan<br/>
                              Wakil Dekan Bidang Akademik dan Kelembagaan
                              <img src="' . $qrCode . '" alt="qrcode ttd dekan" style="width: 100px; height: 100px;" /><br />
                           ' . $sevima_fakultas['data']['nama_wakil_dekan_1'] . '
                              ' . $sevima_fakultas['data']['nama_wakil_dekan_1'] . '<br/>
                              NIP. ' . $sevima_fakultas['data']['nip_wakil_dekan_1'] . '
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </body>
               </html>');

         $dompdf->render();
         $dompdf->stream("magang.pdf", array("Attachment" => false));
      }
   }

   public function submit(): object
   {
      $response = ['status' => false, 'errors' => []];

      $validation = new Validate();
      if ($this->validate($validation->submit())) {
         $model = new Model();
         $submit = $model->submit($this->post);

         $response = array_merge($submit, ['errors' => []]);
      } else {
         $response['msg_response'] = 'Tolong periksa kembali inputan anda!';
         $response['errors'] = \Config\Services::validation()->getErrors();
      }
      return $this->respond($response);
   }

   public function getData(): object
   {
      $response = ['status' => false, 'errors' => []];

      $validation = new Validate();
      if ($this->validate($validation->getData())) {
         $model = new Model();
         $submit = $model->getData($this->post);

         $response = array_merge($submit, ['errors' => []]);
      } else {
         $response['msg_response'] = 'Tolong periksa kembali inputan anda!';
         $response['errors'] = \Config\Services::validation()->getErrors();
      }
      return $this->respond($response);
   }
}
