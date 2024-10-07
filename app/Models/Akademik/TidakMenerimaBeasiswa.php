<?php

namespace App\Models\Akademik;

use App\Models\Common;

class TidakMenerimaBeasiswa extends Common
{

   public function getData(array $post): array
   {
      $table = $this->db->table('tb_surat_pernyataan');
      $table->where('nim', $post['nim']);
      $table->where('concat(tahun_ajaran, semester)', $post['periode']);
      $table->where('jenis_surat', 'beasiswa');

      $get = $table->get();
      $data = $get->getRowArray();
      $fieldNames = $get->getFieldNames();
      $get->freeResult();

      $response = [];
      if (isset($data)) {
         foreach ($fieldNames as $field) {
            $response[$field] = ($data[$field] ? trim($data[$field]) : (string) $data[$field]);
         }
      }
      return $response;
   }
}
