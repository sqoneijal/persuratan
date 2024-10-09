<?php

namespace App\Validation\Akademik;

class TidakMenerimaBeasiswa
{

   public function submit(): array
   {
      return [
         'nim' => [
            'rules' => 'required',
            'label' => 'NIM'
         ],
         'periode' => [
            'rules' => 'required',
            'label' => 'Periode'
         ],
         'id_prodi' => [
            'rules' => 'required',
            'label' => 'Program studi'
         ]
      ];
   }
}
