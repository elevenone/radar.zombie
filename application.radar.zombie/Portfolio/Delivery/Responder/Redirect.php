<?php
namespace Portfolio\Delivery\Responder;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Redirect extends HtmlResponder
{
    protected function success($payload)
    {
        $redirect = $this->request->getAttribute('_redirect', '/mikka2');
        $this->response = $this->response->withStatus(301);
        $this->response = $this->response->withHeader('Location', $redirect);
    }
}
