<?php

namespace App\Controllers;

class Mahasiswa extends BaseController
{
   public function index()
   {
      $response = [];
      return $this->respond($response);
   }
}
