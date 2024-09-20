<?php

namespace App\Controllers\Akademik;

use App\Controllers\BaseController;
use App\Models\Akademik\SuratAktifKuliah as Model;
use App\Validation\Akademik\SuratAktifKuliah as Validate;

class SuratAktifKuliah extends BaseController
{

   public function index(): object
   {
      $response = ['status' => false, 'errors' => []];

      $validation = new Validate();
      if ($this->validate($validation->index())) {
         $model = new Model();
         $submit = $model->getDetailSuratAktifKuliah($this->post);

         $response = array_merge($submit, ['errors' => []]);
      } else {
         $response['message'] = 'Tolong periksa kembali inputan anda!';
         $response['errors'] = \Config\Services::validation()->getErrors();
      }
      return $this->respond($response);
   }
}
