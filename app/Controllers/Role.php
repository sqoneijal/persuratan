<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Role extends BaseController
{
   public function isLogin(): object
   {
      $content = $this->post;
      return $this->respond($content);
   }
}
