<?php

namespace App\Models;

use CodeIgniter\Database\RawSql;

class Refund extends Common
{

   public function submit(array $post): array
   {
      try {
         $table = $this->db->table('tb_refund');
         $table->where('id', $post['id']);
         $table->where('nim', $post['nim']);
         $table->update([
            'nama_rekening' => $post['nama_rekening'],
            'nomor_rekening' => $post['nomor_rekening'],
            'nama_bank_penerima' => $post['nama_bank_penerima'],
            'modified' => new RawSql('now()'),
            'status' => 'sudah'
         ]);
         return ['status' => true, 'msg_response' => 'Data berhasil disimpan.'];
      } catch (\Exception $e) {
         return ['status' => false, 'msg_response' => $e->getMessage()];
      }
   }

   public function getData(string $username): array
   {
      $table = $this->db->table('tb_refund');
      $table->where('nim', $username);
      $table->where('id_semester', static function ($table) {
         return $table->select('id_semester')->from('tb_periode_antara')->where('aktif', true);
      });

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
