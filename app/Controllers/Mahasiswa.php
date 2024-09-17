<?php

namespace App\Controllers;

use App\Libraries\Feeder;

class Mahasiswa extends BaseController
{

   public function index(): object
   {
      $status = false;
      $message = '';
      $content = [];

      $page = intval(@$this->getVar['page']) ?? 0;
      $limit = intval(@$this->getVar['limit']) ?? 10;

      $feeder = new Feeder();
      $feederToken = $feeder->token();

      if ($feederToken['status']) {
         $act = $feeder->action('GetListMahasiswa', $feederToken['token'], [], $limit, $page);

         if ($act['status']) {
            $status = true;
            $content = $act['data'];
         } else {
            $message = $act['message'];
         }
      } else {
         $message = 'Token feeder tidak ditemukan';
      }

      return $this->output($status, $content, $message);
   }

   public function detail(string $nim): object
   {
      $status = false;
      $message = '';
      $content = [];

      $feeder = new Feeder();
      $feederToken = $feeder->token();

      if ($feederToken['status']) {
         $act = $feeder->action('GetListMahasiswa', $feederToken['token'], [
            'nim' => $nim
         ]);

         if ($act['status']) {
            $status = true;
            $content = $act['data'];
         } else {
            $message = $act['message'];
         }
      } else {
         $message = 'Token feeder tidak ditemukan';
      }

      return $this->output($status, $content, $message);
   }
}
