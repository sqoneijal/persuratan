<?php

namespace App\Validation\Akademik;

class SuratAktifKuliah
{

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
