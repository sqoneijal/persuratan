<?php

namespace App\Models\Akademik;

use App\Models\Common;
use CodeIgniter\Database\RawSql;

class TidakMenerimaBeasiswa extends Common
{

   public function getDetailCetak(int $id): array
   {
      $table = $this->db->table('tb_surat_pernyataan');
      $table->where('jenis_surat', 'beasiswa');
      $table->where('id', $id);

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

   public function submit(array $post): array
   {
      try {
         $fields = ['nim', 'id_prodi'];
         foreach ($fields as $field) {
            if (@$post[$field]) {
               $data[$field] = $post[$field];
            } else {
               $data[$field] = null;
            }
         }

         $data['jenis_surat'] = 'beasiswa';
         $data['tahun_ajaran'] = substr($post['periode'], 0, 4);
         $data['semester'] = substr($post['periode'], -1);
         $data['tanggal_pengajuan'] = new RawSql('now()');

         $table = $this->db->table('tb_surat_pernyataan');
         $table->ignore(true)->insert($data);
         return ['status' => true, 'message' => 'Pengajuan berhasil dilakukan.'];
      } catch (\Exception $e) {
         return ['status' => false, 'message' => $e->getMessage()];
      }
   }

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
