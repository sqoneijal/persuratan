<?php

namespace App\Controllers\Akademik;

use App\Controllers\BaseController;
use Dompdf\Dompdf;
use chillerlan\QRCode\QRCode;
use App\Models\Akademik\TidakMenerimaBeasiswa as Model;
use App\Validation\Akademik\TidakMenerimaBeasiswa as Validate;

class TidakMenerimaBeasiswa extends BaseController
{

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

         $linkBerkas = 'https://mael.api.ar-raniry.ac.id/akademik/tidakmenerimabeasiswa/cetak/' . $row['id'];
         $qrCode = (new QRCode)->render($linkBerkas);

         $dompdf = new Dompdf();
         $dompdf->setPaper('A4', 'potrait');
         $dompdf->loadHtml('<!DOCTYPE html>
               <html lang="en">
               <head>
                  <meta charset="UTF-8" />
                  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                  <title>Surat Pernyataan Tidak Menerima Beasiswa</title>
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
                           <td style="text-align: center;">
                              <span style="font-weight: bold;">SURAT PERNYATAAN</span>
                              <br/>
                              <span style="font-weight: bold;">SEDANG TIDAK MENERIMA BEASISWA</span>
                              <br/>
                              Nomor : ' . $row['no_surat'] . '
                           </td>
                        </tr>
                     </tbody>
                  </table>
                  <br/>
                  <table style="width: 100%;">
                     <tbody>
                        <tr>
                           <td style="text-align: justify;" colspan="3">Dekan ' . ucwords($sevima_fakultas['data']['nama']) . ' Islam UIN Ar-Raniry Darussalam Banda Aceh dengan ini menerangkan bahwa:</td>
                        </tr>
                        <tr>
                           <td style="width: 25%;">Nama</td>
                           <td style="width: 1%;">:</td>
                           <td>' . $sevima['data']['nama'] . '</td>
                        </tr>
                        <tr>
                           <td>NIM</td>
                           <td>:</td>
                           <td>' . $sevima['data']['nim'] . '</td>
                        </tr>
                        <tr>
                           <td>Tempat/Tanggal Lahir</td>
                           <td>:</td>
                           <td>' . ucwords($sevima['data']['tempat_lahir']) . ', ' . tanggal_indo($sevima['data']['tanggal_lahir']) . '</td>
                        </tr>
                        <tr>
                           <td>Program Studi</td>
                           <td>:</td>
                           <td>' . $sevima['data']['program_studi'] . '</td>
                        </tr>
                        <tr>
                           <td>Kebangsaan</td>
                           <td>:</td>
                           <td>' . $sevima['data']['nama_negara'] . '</td>
                        </tr>
                        <tr>
                           <td style="vertical-align: top;">Alamat</td>
                           <td style="vertical-align: top;">:</td>
                           <td style="vertical-align: top;">' . $sevima['data']['alamat'] . '</td>
                        </tr>
                        <tr>
                           <td colspan="3" style="text-align: justify;">Saudara yang tersebut namanya di atas adalah benar mahasiswa ' . ucwords($sevima_fakultas['data']['nama']) . ' Islam UIN Ar-Raniry yang terdaftar aktif kuliah periode ' . periode($row['tahun_ajaran'], $row['semester']) . ' dan dinyatakan <span style="font-weight: bold;">tidak sedang menerima beasiswa</span> apapun. Apabila dikemudian hari mahasiswa yang bersangkutan terbukti sedang menerima beasiswa lain, maka surat pernyataan ini dapat ditinjau kembali.<br/><br/>Demikian surat pernyataan ini dikeluarkan untuk dapat dipergunakan seperlunya.</td>
                        </tr>
                     </tbody>
                  </table>
                  <p></p>
                  <table style="width: 100%;">
                     <tbody>
                        <tr>
                           <td style="vertical-align: bottom;"></td>
                           <td style="width: 50%;">
                              Banda Aceh, ' . tanggal_indo(date('Y-m-d', strtotime($row['tanggal_approve']))) . '<br/>
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
         $dompdf->stream("pernyataan_tidak_menerima_beasiswa.pdf", array("Attachment" => false));
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

   public function getData()
   {
      $model = new Model();
      $content = $model->getData($this->post);
      return $this->respond($content);
   }
}
