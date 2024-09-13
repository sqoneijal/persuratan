<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\API\ResponseTrait;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
   /**
    * Instance of the main Request object.
    *
    * @var CLIRequest|IncomingRequest
    */
   protected $request;

   /**
    * An array of helpers to be loaded automatically upon
    * class instantiation. These helpers will be available
    * to all other controllers that extend BaseController.
    *
    * @var list<string>
    */
   protected $helpers = [];

   public $data;
   public $post;
   public $getVar;

   protected $publish = true;

   /**
    * @return void
    */
   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
   {
      // Do Not Edit This Line
      parent::initController($request, $response, $logger);

      $this->post = $request->getPost();
      $this->getVar = $request->getVar();
   }

   public function template(array $params): void
   {
      $params['linkTag'] = $this->generateWebpackCss();
      $params['scriptTag'] = $this->generateWebpackJs();

      echo View('Template', $params);
   }

   public function generateWebpackCss(): string
   {
      if (!$this->publish) {
         return link_tag('http://localhost:8081/App.css');
      } else {
         return link_tag('bundle/app.' . HASH_CSS . '.css');
      }
   }

   public function generateWebpackJs(): string
   {
      if (!$this->publish) {
         return script_tag('http://localhost:8081/App.js');
      } else {
         return script_tag(['type' => 'module', 'src' => 'bundle/app.' . HASH_JS . '.js']);
      }
   }
}
