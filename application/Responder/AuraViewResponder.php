<?php
/**
 *
 * This file is part of Radar.zombie
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Application\Responder;

// use Aura\Payload_Interface\PayloadInterface;
// use Aura\Payload\Payload;
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
class AuraViewResponder implements ResponderAcceptsInterface
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



    protected $viewDir;

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
    public function __invoke(
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

    //
    protected function htmlBody(array $data)
    {

        // Aura.view setup
        $view_factory = new ViewFactory;
        $view = $view_factory->newInstance();

        // add templates to the view registry
        $view_registry = $view->getViewRegistry();

		// if it is not an ajax request render main layout file
		if( ! $this->is_pjax() )
		{
			//
			echo 'aaa';
	        $layout_registry = $view->getLayoutRegistry();
	        $layout_registry->set('layout', $this->path . $this->views['views']['layout']);
		}
		// else
		// {
			//
			// echo 'bbb';
		// }

        // main view
        // $layout_registry = $view->getLayoutRegistry();
        // $layout_registry->set('layout', $this->path . $this->views['views']['layout']);

        // partial view
        $view_registry = $view->getViewRegistry();
        // $partial = $this->request->getAttribute('_content');
        $view_registry->set('_content', $this->path . $this->views['views']['partials']['content']);

        $dataset = [
            'data' => $data, // passing data array to view
            'partial' => 'partial', // passing partial view filename as string to layout
        ];

		// assign data to view
        $view->setData($dataset);

        // set views
        $view->setView('_content');
        $view->setLayout('layout');
        $output = $view->__invoke();

        //
        $this->response = $this->response->withHeader('Content-Type', 'text/html');
        $this->response->getBody()->write($output);
    }

	/**
	 * Checks for ajax request
	 * @return bool
	 */
	protected function is_pjax()
	{
		//echo '1___';
		// if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
		if(isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true')
		{
			echo 'pjax = true';
			return TRUE;
		}
		echo 'pjax = false';
		return FALSE;
	}

    /**
     *
     * Builds a Response for PayloadStatus::SUCCESS.
     *
     */
    protected function success($payload)
    {
        $this->response = $this->response->withStatus(200);
        $this->htmlBody($payload);
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
