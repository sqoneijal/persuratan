<?php

namespace App\Controllers\Akademik;

use App\Controllers\BaseController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dompdf\Dompdf;
use chillerlan\QRCode\QRCode;
use Kreait\Firebase\Factory;

class Khs extends BaseController
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

   private function decodeJwt(string $jwt): array
   {
      return (array) JWT::decode($jwt, new Key('KQYsG4Hi201ajyEzOSGzr4MVfw==', 'HS256'));
   }

   private function logoPath(): string
   {
      $logo_uin = ROOTPATH . 'public/logo_uin.png';
      $type = pathinfo($logo_uin, PATHINFO_EXTENSION);
      $data = file_get_contents($logo_uin);
      return 'data:image/' . $type . ';base64,' . base64_encode($data);
   }

   private function generateHTMLHeader(array $data_jwt, array $sevima, array $sevima_prodi): string
   {
      return '<!DOCTYPE html>
         <html lang="en">
         <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>KHS ' . $data_jwt['periode'] . '</title>
         </head>
         <body>
            <table style="width:100%; border-collapse: collapse;">
               <tr>
                  <td style="width: 60%">
                     <div style="margin: 0; padding: 0; text-align: center;">
                        <div style="padding: 8px 10px 0 0; color: #000; font-size: 13px; text-transform: uppercase;">
                           <img style="width:60px;" src="' . $this->logoPath() . '" alt="UIN AR-RANIRY"><br/>
                           <div style="line-height: 17px; text-transform: none; padding-top: 3px;">
                              <span style="font-size: 15px; font-weight: bold;">UIN AR-RANIRY</span><br/>
                              <span style="font-size: 15px; font-weight: bold;">Universitas Islam Negeri Ar-Raniry Banda Aceh</span><br>
                              <span style="font-size: 12px;">
                                 Jl. Syeikh Abdur Rauf Kopelma Darussalam Banda Aceh Telp/Fax. : 0651-752921<br/>
                                 Keputusan Presiden Nomor 50 tahun 2012 tanggal 25 April 2012.
                              </span>
                           </div>
                           <br style="clear:both" />
                        </div>
                     </div>
                  </td>
                  <td style="width: 40%;">
                     <span style="font-size: 18px; font-weight: bold; margin-top: 10px;">KARTU HASIL STUDI (KHS)</span>
                     <table style="width:100%; line-height: 12px; border-top: 2px solid #000; margin-top: 2px; padding-top: 15px; font-size: 13px;">
                        <tr>
                           <td style="width: 20%;">NIM</td>
                           <td style="width: 2%;">:</td>
                           <td>
                              <strong>' . $sevima['data']['nim'] . '</strong>
                           </td>
                        </tr>
                        <tr>
                           <td>NAMA</td>
                           <td>:</td>
                           <td><strong>' . $sevima['data']['nama'] . '</strong></td>
                        </tr>
                        <tr>
                           <td>PRODI</td>
                           <td>:</td>
                           <td>' . $sevima_prodi['data']['nama_program_studi'] . '</td>
                        </tr>
                        <tr>
                           <td>JENJANG</td>
                           <td>:</td>
                           <td>' . $sevima_prodi['data']['id_jenjang'] . '</td>
                        </tr>
                        <tr>
                           <td>SEMESTER</td>
                           <td>:</td>
                           <td>' . periode(substr($data_jwt['periode'], 0, 4), substr($data_jwt['periode'], 4, 1)) . '</td>
                        </tr>
                     </table>
                  </td>
               </tr>
            </table>
            <table style="width: 100%; border-collapse: collapse;">
               <tr>
                  <th colspan="7" style="padding:1px; border-left: none; border-right: none; border: 1px dotted #000;"></th>
               </tr>
               <tr style="font-size: 12px;">
                  <th rowspan="2" style="border: 1px dotted #000; border-left: none;">NO</th>
                  <th rowspan="2" style="border: 1px dotted #000;">KODE</th>
                  <th rowspan="2" style="border: 1px dotted #000;">NAMA MATAKULIAH</th>
                  <th rowspan="2" style="border: 1px dotted #000;">SKS</th>
                  <th colspan="2" style="border: 1px dotted #000;">NILAI HURUF</th>
                  <th rowspan="2" style="border: 1px dotted #000; border-right: none;">TOTAL<br/>BOBOT</th>
               </tr>
               <tr style="font-size: 12px;">
                  <th style="border: 1px dotted #000; text-align: center;">HURUF</th>
                  <th style="border: 1px dotted #000; text-align: center;">BOBOT</th>
               </tr>';
   }

   private function generateHTMLContent(array $content): string
   {
      $html = '';
      $no = 1;
      foreach ($content as $row) {
         $html .= '<tr style="font-size: 12px;">
            <td style="text-align: center; border: 1px dotted #000; border-left: none;">' . $no . '</td>
            <td style="text-align: center; border: 1px dotted #000;">' . $row['kode_mata_kuliah'] . '</td>
            <td style="border: 1px dotted #000;"><span style="padding-left: 3px;">' . $row['mata_kuliah'] . '</span></td>
            <td style="text-align: center; border: 1px dotted #000;">' . $row['sks'] . '</td>
            <td style="text-align: center; border: 1px dotted #000;">' . $row['nilai_huruf'] . '</td>
            <td style="text-align: center; border: 1px dotted #000;">' . $row['nilai_angka'] . '</td>
            <td style="text-align: center; border: 1px dotted #000; border-right: none;">' . intval($row['nilai_angka']) * intval($row['sks']) . '</td>
         </tr>';
         $no++;
      }
      return $html;
   }

   private function hitungTotalSKSDiambil(array $content): int
   {
      $jumlah = 0;
      foreach ($content as $row) {
         $jumlah += intval($row['sks']);
      }
      return $jumlah;
   }

   private function hitungTotalBobot(array $content): int
   {
      $total_bobot = 0;
      foreach ($content as $row) {
         $total_bobot += intval($row['sks']) * floatval($row['nilai_angka']);
      }
      return $total_bobot;
   }

   private function generateHTMLFooter(array $content, array $attributes, array $data_jwt, string $qrCode, array $sevima_prodi): string
   {

      return '<tr style="font-size: 12px;">
            <td style="font-weight: bold; text-align: right; border: 1px dotted #000; border-left: none;" colspan="3">JUMLAH</td>
            <td style="text-align: center; border: 1px dotted #000;">' . $this->hitungTotalSKSDiambil($content) . '</td>
            <td colspan="2" style="border: 1px dotted #000;"></td>
            <td style="text-align: center; border: 1px dotted #000; border-right: none;">' . $this->hitungTotalBobot($content) . '</td>
         </tr>
         <tr style="font-size: 12px;">
            <td colspan="3" style="border: 1px dotted #000; border-left: none;">
               <table style="width: 100%; margin-top: 20px; margin-bottom: 20px;">
                  <tr>
                     <td style="width: 40%;">Indeks Prestasi (IP)</td>
                     <td style="width: 2%;">:</td>
                     <td style="font-weight: bold;">' . $this->hitungIPS($content) . '</td>
                  </tr>
                  <tr>
                     <td>Indeks Prestasi Kumulatif (IPK)</td>
                     <td>:</td>
                     <td style="font-weight: bold;">' . $this->hitungIPK($attributes, $data_jwt['periode']) . '</td>
                  </tr>
               </table>
            </td>
            <td colspan="4" style="border: 1px dotted #000; border-right: none;"></td>
         </tr>
         <tr>
            <th colspan="7" style="padding:1px; border-left: none; border-right: none; border: 1px dotted #000;"></th>
         </tr>
         </table><br/>
         <table style="width: 100%; font-size: 12px;">
            <tbody>
               <tr>
                  <td style="vertical-align: bottom;"></td>
                  <td style="width: 30%;">
                     Banda Aceh, ' . tanggal_indo(date('Y-m-d')) . '<br/>
                     Ketua Program Studi<br/>
                     <img src="' . $qrCode . '" alt="qrcode ttd dekan" style="width: 100px; height: 100px;" /><br />
                     ' . $sevima_prodi['data']['nama_pimpinan'] . '<br/>
                     NIP. ' . $sevima_prodi['data']['nip_pimpinan'] . '
                  </td>
               </tr>
            </tbody>
         </table>
      </body>
      </html>';
   }

   public function cetak(string $jwt)
   {
      try {
         $data_jwt = $this->decodeJwt($jwt);

         $req = $this->curl->request('GET', 'mahasiswa/' . $data_jwt['nim'] . '/khs');
         $body = json_decode($req->getBody(), true);

         $attributes = [];
         foreach ($body['data'] as $row) {
            $att = $row['attributes'];

            $attributes[] = $att;
         }

         $content = [];
         foreach ($attributes as $row) {
            if ($row['id_periode'] === $data_jwt['periode']) {
               array_push($content, $row);
            }
         }

         $sevima = $this->getDetailBiodata($data_jwt['nim']);

         if ($sevima['status']) {
            $sevima_prodi = $this->getDetailProgramStudi($sevima['data']['id_program_studi']);

            $linkBerkas = 'https://mael.api.ar-raniry.ac.id/akademik/khs/cetak/' . $jwt;
            $qrCode = (new QRCode)->render($linkBerkas);

            $html = $this->generateHTMLHeader($data_jwt, $sevima, $sevima_prodi);
            $html .= $this->generateHTMLContent($content);
            $html .= $this->generateHTMLFooter($content, $attributes, $data_jwt, $qrCode, $sevima_prodi);

            $dompdf = new Dompdf();
            $dompdf->setPaper('A4', 'potrait');
            $dompdf->loadHtml($html);

            $dompdf->render();
            $dompdf->stream("khs_" . $data_jwt['periode'] . "_.pdf", array("Attachment" => false));
         }
      } catch (\Exception $e) {
         die($e->getMessage());
      }
   }

   private function hitungIPK(array $attributes, string $periode): float
   {
      $sks = 0;
      $total_bobot = 0;

      foreach ($attributes as $row) {
         if ($row['id_periode'] <= $periode && $row['is_pakai'] === '1') {
            $sks += intval($row['sks']);
            $total_bobot += intval($row['sks']) * floatval($row['nilai_angka']);
         }
      }

      return round(($total_bobot / $sks), 2);
   }

   private function hitungIPS(array $data): float
   {
      $sks = 0;
      $total_bobot = 0;

      foreach ($data as $row) {
         $sks += intval($row['sks']);
         $total_bobot += intval($row['sks']) * floatval($row['nilai_angka']);
      }

      return round(($total_bobot / $sks), 2);
   }
}
