<?php

namespace App\Controllers\Akademik;

use App\Controllers\BaseController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dompdf\Dompdf;
use chillerlan\QRCode\QRCode;
use App\Libraries\Sevima;

class Transkrip extends BaseController
{

   private function getDetailProgramStudi(string $id_prodi): array
   {
      try {
         $req = $this->curl->request('GET', 'program-studi/' . $id_prodi);
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

   private function getDetailBiodata(string $slug): array
   {
      try {
         $req = $this->curl->request('GET', 'mahasiswa/' . $slug);
         $body = json_decode($req->getBody(), true);

         return ['status' => true, 'data' => $body['attributes']];
      } catch (\Exception $e) {
         return ['status' => false, 'message' => 'Tidak ada data yang ditemukan.'];
      }
   }

   private function logoPath(): string
   {
      $logo_uin = ROOTPATH . 'public/logo_uin.png';
      $type = pathinfo($logo_uin, PATHINFO_EXTENSION);
      $data = file_get_contents($logo_uin);
      return 'data:image/' . $type . ';base64,' . base64_encode($data);
   }

   private function tanggalIndonesia(string $tanggal): string
   {
      // Daftar bulan dalam bahasa Indonesia
      $bulan = array(
         1 => 'Januari',
         2 => 'Februari',
         3 => 'Maret',
         4 => 'April',
         5 => 'Mei',
         6 => 'Juni',
         7 => 'Juli',
         8 => 'Agustus',
         9 => 'September',
         10 => 'Oktober',
         11 => 'November',
         12 => 'Desember'
      );

      // Pisahkan tanggal menjadi tahun, bulan, dan hari
      $pecahkan = explode('-', $tanggal);

      // Format: 27 April 1997
      return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
   }

   private function generateHTMLHeader(array $sevima): string
   {
      $keterangan_transkrip = [
         'S1' => 'SARJANA',
         'S2' => 'MAGISTER',
         'S3' => 'DOKTORAL',
      ];

      return '<!DOCTYPE html>
         <html lang="en">
         <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Transkrip Akhir</title>
         </head>
         <body>
         <img src="' . $this->logoPath() . '" style="width: 70px; position: fixed; top: -10px;" />
         <table style="width: 100%; border-collapse: collapse;">
            <tr>
               <td style="text-align: center; color: #29b157; font-size: 18px; font-weight: bold;">
                  UIN AR-RANIRY<br/>
                  Universitas Islam Negeri<br/>
                  Banda Aceh, Aceh
               </td>
            </tr>
            <tr>
               <td style="text-align: center; color: #29b157; font-size: 12px; border: 1px solid #29b157; border-right: none; border-left: none;">
                  Jl. Syeikh Abdur Rauf Kopelma Darussalam, 23111 Banda Aceh Telp/Fax. : 0651-752921<br/>Perpres RI Nomor 64 tahun 2013 tanggal 1 Oktober 2013.
               </td>
            </tr>
         </table>
         <br/>
         <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <tr>
               <td style="text-align: center; font-weight: bold;">
                  <span style="text-decoration: underline; font-size: 18px;">TRANSKRIP AKADEMIK</span><br/>
                  <span style="font-size: 15px;">PROGRAM ' . $keterangan_transkrip[$sevima['data']['id_jenjang']] . '</span>
               </td>
            </tr>
         </table>
         <br/>
         <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <tr>
               <td style="width: 23%;">NIM</td>
               <td style="width: 2%">:</td>
               <td>' . $sevima['data']['nim'] . '</td>
            </tr>
            <tr>
               <td>NAMA</td>
               <td>:</td>
               <td>' . $sevima['data']['nama'] . '</td>
            </tr>
            <tr>
               <td>TEMPAT/TANGGAL LAHIR</td>
               <td>:</td>
               <td>' . $sevima['data']['tempat_lahir'] . ', ' . $this->tanggalIndonesia($sevima['data']['tanggal_lahir']) . '</td>
            </tr>
            <tr>
               <td>PROGRAM STUDI</td>
               <td>:</td>
               <td>' . $sevima['data']['id_jenjang'] . ' ' . $sevima['data']['program_studi'] . '</td>
            </tr>
         </table>
         <br/>';
   }

   private function generateHTMLContent(array $attributes): string
   {
      $html = '<table style="width: 100%; border-collapse: collapse; font-size: 10px;">
         <thead>
            <tr><td style="border: 1px dotted #000; border-right: none; border-left: none;" colspan="12"></td></tr>
            <tr>
               <th style="width: 3%; border-left: 1px dotted #000; border-bottom: 1px dotted #000;" rowspan="2">NO</th>
               <th style="width: 7%; border-bottom: 1px dotted #000;" rowspan="2">KODE</th>
               <th style="border-bottom: 1px dotted #000;" rowspan="2">NAMA MATAKULIAH</th>
               <th style="width: 3%; border-bottom: 1px dotted #000;" rowspan="2">SKS</th>
               <th colspan="2" style="border-right: 1px dotted #000;">NILAI</th>
               <th style="width: 3%; border-bottom: 1px dotted #000;" rowspan="2">NO</th>
               <th style="width: 7%; border-bottom: 1px dotted #000;" rowspan="2">KODE</th>
               <th style="border-bottom: 1px dotted #000;" rowspan="2">NAMA MATAKULIAH</th>
               <th style="width: 3%; border-bottom: 1px dotted #000;" rowspan="2">SKS</th>
               <th colspan="2" style="border-right: 1px dotted #000;">NILAI</th>
            </tr>
            <tr>
               <th style="width: 3%; border-bottom: 1px dotted #000;">HURUF</th>
               <th style="width: 3%; border-bottom: 1px dotted #000; border-right: 1px dotted #000;">BOBOT</th>
               <th style="width: 3%; border-bottom: 1px dotted #000;">HURUF</th>
               <th style="width: 3%; border-bottom: 1px dotted #000; border-right: 1px dotted #000;">BOBOT</th>
            </tr>
         </thead><tbody>';

      $no = 1;
      $total_sks = 0;
      $total_bobot = 0;
      $counter = ceil(count($attributes) / 2);

      for ($index = 0; $index < $counter; $index++) {
         $total_sks += $attributes[$index]['sks_mata_kuliah'];
         $total_bobot += $attributes[$index]['sks_mata_kuliah'] * $attributes[$index]['nilai_angka'];

         $html .= '<tr>';
         $html .= '<td style="text-align: center; border-left: 1px dotted #000;">' . $no . '</td>
            <td style="text-align: center;">' . $attributes[$index]['kode_mata_kuliah'] . '</td>
            <td>' . $attributes[$index]['nama_mata_kuliah'] . '</td>
            <td style="text-align: center;">' . $attributes[$index]['sks_mata_kuliah'] . '</td>
            <td style="text-align: center;">' . $attributes[$index]['nilai_huruf'] . '</td>
            <td style="text-align: center;">' . $attributes[$index]['nilai_angka'] * $attributes[$index]['sks_mata_kuliah'] . '</td>';

         if (isset($attributes[$index + $counter])) {
            $total_sks += $attributes[$index + $counter]['sks_mata_kuliah'];
            $total_bobot += $attributes[$index + $counter]['sks_mata_kuliah'] * $attributes[$index + $counter]['nilai_angka'];

            $html .= '<td style="text-align: center; border-left: 1px dotted #000;">' . ($no + $counter) . '</td>
               <td style="text-align: center;">' . $attributes[$index + $counter]['kode_mata_kuliah'] . '</td>
               <td>' . $attributes[$index + $counter]['nama_mata_kuliah'] . '</td>
               <td style="text-align: center;">' . $attributes[$index + $counter]['sks_mata_kuliah'] . '</td>
               <td style="text-align: center;">' . $attributes[$index + $counter]['nilai_huruf'] . '</td>
               <td style="text-align: center; border-right: 1px dotted #000;">' . $attributes[$index + $counter]['nilai_angka'] * $attributes[$index + $counter]['sks_mata_kuliah'] . '</td>';
         } else {
            $html .= '<td style="border: 1px dotted #000; border-top: none; border-bottom: none;" colspan="6">&nbsp;</td>';
         }
         $html .= '</tr>';
         $no++;
      }

      $html .= '</tbody>
      <tfoot>
         <tr><td style="border: 1px dotted #000; border-right: none; border-left: none;" colspan="12"></td></tr>
         <tr>
            <td colspan="6">&nbsp;</td>
            <td colspan="6">
               <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
                  <tr>
                     <td style="width: 50%;">Total Satuan Kredit Semester (SKS)</td>
                     <td style="width: 2%;">:</td>
                     <td><strong>' . $total_sks . '</strong> SKS</td>
                  </tr>
                  <tr>
                     <td>Total Bobot</td>
                     <td>:</td>
                     <td><strong>' . $total_bobot . '</strong></td>
                  </tr>
                  <tr>
                     <td>Indeks Prestasi Kumulatif</td>
                     <td>:</td>
                     <td><strong>' . ($total_bobot > 0 ? round($total_bobot / $total_sks, 2) : '0') . '</strong></td>
                  </tr>
               </table>
            </td>
         </tr>
      </tfoot>
      </table>';
      return $html;
   }

   private function generateHTMLFooter(string $jwt, array $sevima_fakultas): string
   {
      $linkBerkas = 'https://mael.api.ar-raniry.ac.id/akademik/transkrip/cetak/' . $jwt;
      $qrCode = (new QRCode)->render($linkBerkas);

      return '<table style="width: 100%; font-size: 12px; border-collapse: collapse;">
            <tbody>
               <tr>
                  <td style="vertical-align: bottom;"></td>
                  <td style="width: 30%;">
                     Banda Aceh, ' . tanggal_indo(date('Y-m-d')) . '<br/>
                     Wakil Dekan I<br/>
                     <img src="' . $qrCode . '" alt="qrcode ttd dekan" style="width: 70px; height: 70px;" /><br />
                     ' . $sevima_fakultas['data']['nama_wakil_dekan_1'] . '<br/>
                     NIP. ' . $sevima_fakultas['data']['nip_wakil_dekan_1'] . '
                  </td>
               </tr>
            </tbody>
         </table></body>
         </html>';
   }

   public function cetak(string $jwt)
   {
      try {
         $sevima = new Sevima();
         $data_jwt = $this->decodeJwt($jwt);

         $req = $this->curl->request('GET', 'mahasiswa/' . $data_jwt['nim'] . '/transkrip');
         $body = json_decode($req->getBody(), true);

         $attributes = [];
         foreach ($body['data'] as $row) {
            $attributes[] = $row['attributes'];
         }

         $sevima_biodata = $this->getDetailBiodata($data_jwt['nim']);

         if ($sevima_biodata['status']) {
            $sevima_prodi = $sevima->getDetailProgramStudi($sevima_biodata['data']['id_program_studi']);
            $sevima_fakultas = $sevima->getDetailFakultas($sevima_prodi['data']['id_fakultas']);

            $html = $this->generateHTMLHeader($sevima_biodata);
            $html .= $this->generateHTMLContent($attributes);
            $html .= $this->generateHTMLFooter($jwt, $sevima_fakultas);

            $dompdf = new Dompdf();
            $dompdf->setPaper('A4', 'potrait');
            $dompdf->loadHtml($html);

            $dompdf->render();
            $dompdf->stream("transkrip_akhir.pdf", array("Attachment" => false));
            exit();
         }
      } catch (\Exception $e) {
         die($e->getMessage());
      }
   }
}
