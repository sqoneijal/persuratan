<?php

namespace App\Controllers\Akademik;

use App\Controllers\BaseController;
use App\Models\Akademik\TidakMenerimaBeasiswa as Model;

class TidakMenerimaBeasiswa extends BaseController
{

   public function getData()
   {
      $model = new Model();
      $content = $model->getData($this->post);
      return $this->respond($content);
   }
}
