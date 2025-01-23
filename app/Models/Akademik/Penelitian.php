<?php

namespace App\Models\Akademik;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Model;
use App\Libraries\Sevima;

class Penelitian extends Model
{

   private function checkBiodata(string $nim): bool
   {
      $table = $this->db->table('tb_mahasiswa');
      $table->where('nim', $nim);

      return $table->countAllResults() > 0 ? true : false;
   }

   private function generateBiodataMahasiswa(string $nim): void
   {
      $sevima = new Sevima();
      $data = $sevima->getBiodataMahasiswa($nim);

      $checkBiodata = $this->checkBiodata($nim);
      if (!$checkBiodata) {
         $table = $this->db->table('tb_mahasiswa');
         $table->insert([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'tmp_lahir' => $data['tempat_lahir'],
            'tgl_lahir' => $data['tanggal_lahir'],
            'jekel' => $data['jenis_kelamin'],
            'id_prodi' => $data['id_program_studi'],
            'alamat' => $data['alamat']
         ]);
      }
   }

   public function submit(array $post): array
   {
      try {
         $this->generateBiodataMahasiswa($post['nim']);

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
         $table->where('jenis_surat', 'penelitian');
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
