<?php

namespace App\Validation\Akademik;

class SuratAktifKuliah
{

   public function pengajuan(): array
   {
      return [
         'nim' => [
            'rules' => 'required|numeric',
            'label' => 'NIM'
         ],
         'periode' => [
            'rules' => 'required|numeric',
            'label' => 'Periode'
         ]
      ];
   }

   public function index(): array
   {
      return [
         'nim' => [
            'rules' => 'required',
            'label' => 'NIM'
         ],
         'periode' => [
            'rules' => 'required',
            'label' => 'Periode'
         ]
      ];
   }
}
