<?php

namespace App\Controllers\Akademik;

use App\Controllers\BaseController;

class SuratAktifKuliah extends BaseController
{

   public function index(string $nim): object
   {
      return $this->respond([]);
   }
}
