<?php

namespace App\Controllers\Akademik;

use App\Controllers\BaseController;
use App\Models\Akademik\SuratAktifKuliah as Model;
use App\Validation\Akademik\SuratAktifKuliah as Validate;
use Dompdf\Dompdf;
use chillerlan\QRCode\QRCode;

class SuratAktifKuliah extends BaseController
{

   public function status(): object
   {
      $response = ['status' => false, 'errors' => []];

      $validation = new Validate();
      if ($this->validate($validation->index())) {
         $model = new Model();
         $submit = $model->getDetailSuratAktifKuliah($this->post);

         $response = array_merge($submit, ['errors' => []]);
      } else {
         $response['message'] = 'Tolong periksa kembali inputan anda!';
         $response['errors'] = \Config\Services::validation()->getErrors();
      }
      return $this->respond($response);
   }

   public function pengajuan(): object
   {
      $response = ['status' => false, 'errors' => []];

      $validation = new Validate();
      if ($this->validate($validation->pengajuan())) {
         $model = new Model();
         $submit = $model->pengajuan($this->post);

         $response = array_merge($submit, ['errors' => []]);
      } else {
         $response['message'] = 'Tolong periksa kembali inputan anda!';
         $response['errors'] = \Config\Services::validation()->getErrors();
      }
      return $this->respond($response);
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

         $ket_semester = [
            '1' => 'Ganjil',
            '2' => 'Genap'
         ];

         $linkBerkas = 'https://berkas.ar-raniry.ac.id/aktifkuliah/' . $row['nim'] . $row['thn_ajaran'] . $row['id_semester'];
         $qrCode = (new QRCode)->render($linkBerkas);

         $dompdf = new Dompdf();
         $dompdf->setPaper('A4', 'potrait');
         $dompdf->loadHtml('<!DOCTYPE html>
         <html lang="en">
         <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Surat Aktif Kuliah</title>
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
                     <td style="text-align: center; font-weight: bold;">SURAT KETERANGAN AKTIF KULIAH</td>
                  </tr>
                  <tr>
                     <td style="text-align: center;">Nomor : ' . $row['no_surat'] . '</td>
                  </tr>
               </tbody>
            </table>
            <br />
            <table style="width: 100%;">
               <tbody>
                  <tr>
                     <td colspan="3" style="text-align: justify;">Dekan ' . $sevima_fakultas['data']['nama'] . ' UIN Ar-Raniry Banda Aceh, dengan ini menerangkan bahwa :<br /><br /></td>
                  </tr>
                  <tr>
                     <td style="width: 20%;">Nama Mahasiswa</td>
                     <td style="width: 1%">:</td>
                     <td>' . $sevima['data']['nama'] . '</td>
                  </tr>
                  <tr>
                     <td>NIM</td>
                     <td>:</td>
                     <td>' . $sevima['data']['nim'] . '</td>
                  </tr>
                  <tr>
                     <td>Alamat</td>
                     <td>:</td>
                     <td>' . $sevima['data']['alamat'] . '</td>
                  </tr>
                  <tr>
                     <td colspan="3" style="text-align: justify;"><br />Benar yang namanya tersebut di atas terdaftar sebagai Mahasiswa pada Program studi ' . $sevima['data']['program_studi'] . ' ' . $sevima_fakultas['data']['nama'] . ' UIN Ar-Raniry Banda Aceh Semester ' . @$ket_semester[$row['id_semester']] . ' Tahun Akademik ' . $row['thn_ajaran'] . '/' . (intval($row['thn_ajaran']) + 1) . '.<br /><br />Demikian surat keterangan ini diberikan untuk dapat dipergunakan seperlunya.</td>
                  </tr>
               </tbody>
            </table>
            <br />
            <table style="width: 100%;">
               <tbody>
                  <tr>
                     <td style="width: 50%;">&nbsp;</td>
                     <td>Banda Aceh, ' . tanggal_indo($row['tgl_surat']) . '<br />
                        An. Dekan<br />
                        Wakil Dekan Bidang Akademik dan Kelembagaan<br />
                        <img src="' . $qrCode . '" alt="qrcode ttd dekan" style="width: 100px; height: 100px;" /><br />
                        ' . $sevima_fakultas['data']['nama_wakil_dekan_1'] . '<br />
                        NIP. ' . $sevima_fakultas['data']['nip_wakil_dekan_1'] . '
                     </td>
                  </tr>
               </tbody>
            </table>
         </body>

         </html>');
         $dompdf->render();
         $dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
      }
   }
}
