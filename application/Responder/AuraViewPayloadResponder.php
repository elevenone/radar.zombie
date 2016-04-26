<?php
/**
 *
 * This file is part of Radar.zombie
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Application\Responder;

use Aura\Payload_Interface\PayloadInterface;
use Aura\Payload\Payload;
use Aura\View\ViewFactory as ViewFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Radar\Adr\Responder\ResponderAcceptsInterface;


/**
 *
 * Aura view Responder.
 *
 * @package radar.zombie
 *
 */
class AuraViewPayloadResponder implements ResponderAcceptsInterface
{
    /**
     *
     * The domain payload (i.e. the output from the domain).
     *
     * @var PayloadInterface
     *
     */
    protected $payload;

    /**
     *
     * The HTTP request.
     *
     * @var Request
     *
     */
    protected $request;

    /**
     *
     * The HTTP response.
     *
     * @var Response
     *
     */
    protected $response;

    /**
     * Rules
     *
     * @var array
     *
     * @access protected
     */
    protected $views;

	/**
     * __construct
     *
     * @param array $rules Map of rules
     *
     * @access public
     */
	// todo // get an array with the files
    public function __construct($views) // $viewDir
    {
        $this->views = $views;
        $this->path = $this->views['views']['path'];
    }



    /**
     *
     * Returns the list of media types this Responder can generate.
     *
     * @return array
     *
     */
    public static function accepts()
    {
        return ['text/html'];
    }

    /**
     *
     * Builds and returns the Response using the Request and Payload.
     *
     * @param Request $request The HTTP request object.
     *
     * @param Response $response The HTTP response object.
     *
     * @param PayloadInterface $payload The domain payload object.
     *
     * @return Response
     *
     */
    public function __invokeX(
        Request $request,
        Response $response,
        array $payload
    ) {
        $this->request = $request;
        $this->response = $response;
        if (isset($payload['success']) && true === $payload['success']) {
            $this->success($payload);
        } else {
            $this->error($payload);
        }
        return $this->response;
    }

    public function __invoke(
        Request $request,
        Response $response,
        PayloadInterface $payload = null
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->payload = $payload;
        $method = $this->getMethodForPayload();
        $this->$method();                                   ///////////
        return $this->response;
    }

    /**
     *
     * Returns the Responder method to call, based on the Payload status.
     *
     * @return string
     *
     */
    protected function getMethodForPayload()
    {
        if (! $this->payload) {
            return 'noContent';
        }

        $method = str_replace('_', '', strtolower($this->payload->getStatus()));
        return method_exists($this, $method) ? $method : 'unknown';
    }



    //
    protected function htmlBody($data)
    {

        // Aura.view setup
        $view_factory = new ViewFactory;
        $view = $view_factory->newInstance();

        // add templates to the view registry
        $view_registry = $view->getViewRegistry();

        // main view
        $layout_registry = $view->getLayoutRegistry();
        $layout_registry->set('layout', $this->path . $this->views['views']['layout']);

        // partial view
        $view_registry = $view->getViewRegistry();
        // $partial = $this->request->getAttribute('_content');
        $view_registry->set('_content', $this->path . $this->views['views']['partials']['content']); // set partial view main content file as dynamic partial

        $dataset = [
            'data' => $data, // passing data array to view
            'partial' => 'partial', // passing partial view filename as string to layout
        ];

        $view->setData($dataset); // do it

        // set views
        $view->setView('_content');
        $view->setLayout('layout');
        $output = $view->__invoke(); // or just $view()





        //
        $this->response = $this->response->withHeader('Content-Type', 'text/html');
        $this->response->getBody()->write($output);
    }

    /**
     *
     * Builds a Response for PayloadStatus::SUCCESS.
     *
     */
    protected function success()
    {
        $this->response = $this->response->withStatus(200);
        $this->htmlBody($this->payload->getOutput());
    }

    /**
     *
     * Builds a Response for PayloadStatus::ERROR.
     *
     */
    protected function error($payload)
    {
        $this->response = $this->response->withStatus(500);
        $this->request = $this->request->withAttribute('_view', 'error.php');
        $this->htmlBody($payload);
    }
}
