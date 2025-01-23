<?php

namespace App\Models\Akademik;

use App\Models\Common;
use CodeIgniter\Database\RawSql;
use App\Libraries\Sevima;

class Magang extends Common
{

   public function getDetailCetak(int $id): array
   {
      $table = $this->db_siakad->table('tb_surat_magang');
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
         $fields = ['tujuan_surat', 'lokasi_magang', 'no_kontak', 'alamat', 'id_prodi', 'nim', 'nama'];
         foreach ($fields as $field) {
            if (@$post[$field]) {
               $data[$field] = $post[$field];
            } else {
               $data[$field] = null;
            }
         }

         $data['tahun_ajaran'] = substr($post['periode'], 0, 4);
         $data['id_semester'] = substr($post['periode'], -1);
         $data['uploaded'] = new RawSql('now()');

         $table = $this->db_siakad->table('tb_surat_magang');
         $table->ignore(true)->insert($data);

         return ['status' => true, 'message' => 'Pengajuan surat magang berhasil dilakukan.'];
      } catch (\Exception $e) {
         return ['status' => false, 'message' => $e->getMessage()];
      }
   }

   public function getData(array $post): array
   {
      try {
         $table = $this->db_siakad->table('tb_surat_magang');
         $table->where('nim', $post['nim']);
         $table->where('concat(tahun_ajaran, id_semester)', $post['periode']);

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
}
