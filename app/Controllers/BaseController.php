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

   use ResponseTrait;

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
   protected $helpers = ['html', 'autoload'];

   public $data;
   public $post;
   public $getVar;

   public $curl;

   /**
    * @return void
    */
   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
   {
      // Do Not Edit This Line
      parent::initController($request, $response, $logger);

      $this->post = $request->getPost();
      $this->getVar = $request->getVar();

      $this->curl = service('curlrequest', [
         'baseURI' => env('SEVIMA_PATH_URL'),
         'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-App-Key' => env('SEVIMA_APP_KEY'),
            'X-Secret-Key' => env('SEVIMA_APP_SECRET')
         ]
      ]);
   }

   public function output(bool $status, array $content, string $message = '')
   {
      if ($message === '') {
         $msg_response = $status ? 'OK' : 'Not OK';
      } else {
         $msg_response = $message;
      }

      return $this->respond(['status' => $status, 'data' => $content, 'message' => $msg_response]);
   }
}
