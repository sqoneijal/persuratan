<?php

namespace App\Models;

class SertifikatKPM extends Common
{

   public function getData(string $nim): array
   {
      $table = $this->db_kpm->table('tb_peserta_kpm');
      $table->where('nim', $nim);
      $table->where('boleh_ikut_kpm', 't');

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

   public function getDetailCetak(int $id): array
   {
      $table = $this->db_kpm->table('tb_peserta_kpm');
      $table->where('id', $id);
      $table->where('boleh_ikut_kpm', 't');

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
