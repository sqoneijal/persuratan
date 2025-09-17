<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Refund as Model;

class Refund extends BaseController
{
   public function getData()
   {
      $model = new Model();
      $data = $model->getData(@$this->post['username']);
      return $this->respond($data);
   }

   public function submit(): object
   {
      $response = ['status' => false, 'errors' => []];

      if ($this->validate($this->form_validation())) {
         $model = new Model();
         $submit = $model->submit($this->post);

         $response = array_merge($submit, ['errors' => []]);
      } else {
         $response['msg_response'] = 'Tolong periksa kembali inputan anda!';
         $response['errors'] = \Config\Services::validation()->getErrors();
      }
      return $this->respond($response);
   }

   private function form_validation()
   {
      return [
         'nama_rekening' => [
            'rules' => 'required',
            'label' => 'Nama rekening'
         ],
         'nomor_rekening' => [
            'rules' => 'required',
            'label' => 'Nomor rekening'
         ],
         'nama_bank_penerima' => [
            'rules' => 'required',
            'label' => 'Nama bank'
         ]
      ];
   }
}
