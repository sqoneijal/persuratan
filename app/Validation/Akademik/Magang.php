<?php

namespace App\Validation\Akademik;

class Magang
{

   public function getData(): array
   {
      return [
         'nim' => [
            'rules' => 'required|numeric',
            'label' => 'NIM'
         ],
         'periode' => [
            'rules' => 'required|numeric|exact_length[5]',
            'label' => 'Periode'
         ]
      ];
   }

   public function submit(): array
   {
      return [
         'nim' => [
            'rules' => 'required|numeric',
            'label' => 'NIM'
         ],
         'periode' => [
            'rules' => 'required|numeric|exact_length[5]',
            'label' => 'Periode'
         ],
         'tujuan_surat' => [
            'rules' => 'required',
            'label' => 'Tujuan surat'
         ],
         'lokasi_magang' => [
            'rules' => 'required',
            'label' => 'Lokasi magang'
         ],
         'no_kontak' => [
            'rules' => 'required',
            'label' => 'Nomor hp yang aktif'
         ],
         'alamat' => [
            'rules' => 'required',
            'label' => 'Alamat tinggal sekarang'
         ]
      ];
   }
}
