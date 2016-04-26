<?php

namespace Application\Responder;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Radar\Adr\Responder\ResponderAcceptsInterface;

class Html implements ResponderAcceptsInterface
{
    protected $request;

    protected $response;

    protected $payload;

    protected $template;

    public function __construct(Twig_Environment $template)
    {
        $this->template = $template;
    }

    public static function accepts(): array
    {
        return ['text/html'];
    }

    public function __invoke(
        Request $request,
        Response $response,
        array $payload = null
    ): Response {
        $this->request = $request;
        $this->response = $response;
        $this->payload = $payload;
        if (true === $this->payload['success']) {
            $this->success();
        } else {
            $this->notFound();
        }
        return $this->response;
    }

    protected function htmlBody(array $data)
    {
        if (isset($data)) {
            // template logic
			$view = $this->request->getAttribute('_view', '/view.html');
            $template = $this->this->template->loadTemplate($view);
            $body = $template->render($data);
            $this->response = $this->response->withHeader('Content-Type', 'text/html');
            $this->response->getBody()->write($body);
        }
    }

    protected function success()
    {
        $this->response = $this->response->withStatus(200);
        $this->htmlBody($this->payload);
    }

    protected function notFound()
    {
        $this->response = $this->response->withStatus(404);
        $this->request = $this->request->withAttribute('_view', '/app/views/notfound.twig.html');
        $this->htmlBody($this->payload);
    }
}