<?php

namespace App\Models\Akademik;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;

class Penelitian extends Model
{

   public function submit(array $post): array
   {
      try {
         $fields = ['nim', 'surat_kepada', 'judul_penelitian', 'id_prodi'];
         foreach ($fields as $field) {
            if (@$post[$field]) {
               $data[$field] = $post[$field];
            } else {
               $data[$field] = null;
            }
         }

         $data['tahun_ajaran'] = substr($post['periode'], 0, 4);
         $data['semester'] = substr($post['periode'], -1);
         $data['jenis_surat'] = 'penelitian';
         $data['tanggal_pengajuan'] = new RawSql('now()');

         $table = $this->db->table('tb_surat_pernyataan');
         $table->ignore(true)->insert($data);

         return ['status' => true, 'data' => $this->status($post), 'message' => 'Pengajuan berhasil dilakukan.'];
      } catch (\Exception $e) {
         return ['status' => false, 'message' => $e->getMessage()];
      }
   }

   public function status(array $post): array
   {
      try {
         $table = $this->db->table('tb_surat_pernyataan');
         $table->where('nim', $post['nim']);
         $table->where('concat(tahun_ajaran, semester)', $post['periode']);

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

         return ['status' => true, 'data' => $response];
      } catch (\Exception $e) {
         return ['status' => false, 'message' => $e->getMessage()];
      }
   }

   public function getDetailCetak(int $id): array
   {
      $table = $this->db->table('tb_surat_pernyataan');
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
}
