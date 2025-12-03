<?php

namespace App\Models;

use CodeIgniter\Model;

class Common extends Model
{

   protected $db;
   protected $mael;
   protected $kpm;

   public function __construct()
   {
      parent::__construct();

      $this->db = \Config\Database::connect('default');
      $this->mael = \Config\Database::connect('mael');
      $this->db_kpm = \Config\Database::connect('kpm');
   }

   public function insertMahasiswa(array $post)
   {
      $check = $this->checkMahasiswa($post['nim']);

      $table = $this->db->table('tb_mahasiswa');
      if (!$check) {
         $table->insert([
            'nama' => $post['nama'],
            'tmp_lahir' => $post['tempat_lahir'],
            'tgl_lahir' => $post['tanggal_lahir'],
            'jekel' => $post['jenis_kelamin'],
            'id_prodi' => $post['id_program_studi'],
            'nim' => $post['nim'],
            'alamat' => $post['alamat'],
         ]);
      } else {
         $table->where('nim', $post['nim']);
         $table->insert([
            'nama' => $post['nama'],
            'tmp_lahir' => $post['tempat_lahir'],
            'tgl_lahir' => $post['tanggal_lahir'],
            'jekel' => $post['jenis_kelamin'],
            'id_prodi' => $post['id_program_studi'],
            'alamat' => $post['alamat'],
         ]);
      }
   }

   public function checkMahasiswa($nim): bool
   {
      $table = $this->db->table('tb_mahasiswa');
      $table->where('nim', $nim);

      return $table->countAllResults() > 0;
   }
}
