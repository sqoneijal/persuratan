<?php

namespace App\Models\Akademik;

use App\Models\Common;

class SuratAktifKuliah extends Common
{

   public function getDetailSuratAktifKuliah(array $post): array
   {
      try {
         $table = $this->db->table('tb_aktif_kuliah');
         $table->where('nim', $post['nim']);
         $table->where('concat(thn_ajaran, id_semester)', $post['periode']);

         $get = $table->get();
         $data = $get->getRowArray();
         $fieldNames = $get->getFieldNames();
         $get->freeResult();

         if (isset($data)) {
            $response = [];
            foreach ($fieldNames as $field) {
               $response[$field] = ($data[$field] ? trim($data[$field]) : (string) $data[$field]);
            }
            return ['status' => true, 'data' => $response];
         }
         return ['status' => false, 'message' => 'Data tidak ditemukan.'];
      } catch (\Exception $e) {
         return ['status' => false, 'message' => $e->getMessage()];
      }
   }
}
