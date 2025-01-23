<?php

namespace App\Models\Akademik;

use App\Models\Common;
use CodeIgniter\Database\RawSql;
use App\Libraries\Sevima;

class SuratAktifKuliah extends Common
{

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

   private function checkBiodata(string $nim): bool
   {
      $table = $this->db->table('tb_mahasiswa');
      $table->where('nim', $nim);

      return $table->countAllResults() > 0 ? true : false;
   }

   public function pengajuan(array $post): array
   {
      try {
         $this->generateBiodataMahasiswa($post['nim']);

         $tahun_ajaran = substr($post['periode'], 0, 4);
         $id_semester = substr($post['periode'], 4, 1);

         $table = $this->db->table('tb_aktif_kuliah');
         $table->ignore(true)->insert([
            'thn_ajaran' => $tahun_ajaran,
            'id_semester' => $id_semester,
            'uploaded' => new RawSql('now()'),
            'nim' => $post['nim']
         ]);

         return ['status' => true, 'data' => $this->getDetailSuratAktifKuliah($post)['data'], 'message' => 'Pengajuan berhasil dilakukan.'];
      } catch (\Exception $e) {
         return ['status' => false, 'message' => $e->getMessage()];
      }
   }

   public function getDetailCetak(int $id): array
   {
      $table = $this->db->table('tb_aktif_kuliah');
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
