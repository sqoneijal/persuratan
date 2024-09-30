<?php

namespace App\Validation\Akademik;

class Penelitian
{

   public function submit(): array
   {
      return [
         'nim' => [
            'rules' => 'required|numeric',
            'label' => 'NIM'
         ],
         'periode' => [
            'rules' => 'required|numeric',
            'label' => 'Periode'
         ],
         'surat_kepada' => [
            'rules' => 'required',
            'label' => 'Tujuan surat'
         ],
         'judul_penelitian' => [
            'rules' => 'required',
            'label' => 'Judul penelitian'
         ],
      ];
   }

   public function status(): array
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
}
