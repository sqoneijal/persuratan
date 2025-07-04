<?php

namespace App\Models;

use CodeIgniter\Model;

class Common extends Model
{

   protected $db;
   protected $db_siakad;
   protected $mael;

   public function __construct()
   {
      parent::__construct();

      $this->db = \Config\Database::connect('default');
      $this->mael = \Config\Database::connect('mael');
      $this->db_siakad = \Config\Database::connect('siakad');
   }
}
