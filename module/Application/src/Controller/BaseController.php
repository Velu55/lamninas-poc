<?php 

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Http\Response;

class BaseController extends AbstractActionController
{
    protected function setCorsHeaders()
    {
        $response = $this->getResponse();
        $response->getHeaders()
        ->addHeaderLine('Access-Control-Allow-Origin', '*')
        ->addHeaderLine('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->addHeaderLine('Access-Control-Allow-Headers', 'Authorization, Content-Type');
    }
}
