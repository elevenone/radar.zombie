<?php
/**
 * Static view responder using aura payload
 *
 * @category Responder
 * @package  Application
 *
 */

namespace Application\Responder;

use Aura\Payload_Interface\PayloadInterface;
use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus;
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
// class AuraViewStaticPage implements ResponderAcceptsInterface
class AuraViewStaticPage extends AbstractResponder
{
    /**
     * The domain payload (i.e. the output from the domain).
     * @var PayloadInterface
     */
    protected $payload;

    /**
     * The HTTP request.
     * @var Request
     */
    protected $request;

    /**
     * The HTTP response.
     * @var Response
     */
    protected $response;

    /**
     * Views file path
     * @var array
     * @access protected
     */
    protected $views;

    /**
     * __construct
     * @param array $rules Map of rules
     * @access public
     */
    // todo // get an array with the files
    public function __construct($views) // $viewDir
    {
        // view filepaths from $views array
        $this->views = $views;

        // template root path
        $this->template_path = $views['views']['path'];

        // layout view file
        $this->layout = $this->template_path . $views['views']['layout'];

        // staticpages path. The __ underscores are for safety, for not including a file defined from url
        $this->staticpages = $views['views']['staticpages_path'] . DIRECTORY_SEPARATOR . '__';
    }

    /**
     * Returns the list of media types this Responder can generate.
     * @return array
     */
    public static function accepts()
    {
        return ['text/html'];
    }

    /**
     * Builds and returns the Response using the Request and Payload.
     * @param Request $request The HTTP request object.
     * @param Response $response The HTTP response object.
     * @param PayloadInterface $payload The domain payload object.
     * @return Response
     */
    public function __invoke(
        Request $request,
        Response $response,
        PayloadInterface $payload = null
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->payload = $payload;
        $method = $this->getMethodForPayload();
        $this->$method();
        return $this->response;
    }

    /**
     * Returns the Responder
     * @return string
     */
    protected function htmlBody($data)
    {
        //
        // $slug = 'error';
        if (isset($data))
		{

            // $slug = $this->request->getAttribute('page');
            $slugFromPayload = $this->payload->getOutput();
            $slug = $slugFromPayload['slug'];

			// if (!isset($slugFromPayload['slug']))
			// {
			//	$slug = 'index';
			// }
            // $this->request = $this->request->withAttribute('page', 'error.php');
            //setup views
//            $this->loadTemplate();
//            $template = $this->twig->loadTemplate($view);
//            $body = $template->render($data);
//            $this->response = $this->response->withHeader('Content-Type', 'text/html');
//            $this->response->getBody()->write($body);
        }

// set thir probaly fro config file
        // Aura.view setup
        $view_factory = new ViewFactory; // a
        $view = $view_factory->newInstance(); // 

        // layout
        $layout_registry = $view->getLayoutRegistry();
        $layout_registry->set('layout', $this->layout);

        // views
        $view_registry = $view->getViewRegistry();

        $slug = $this->request->getAttribute('page');
        $partial_view = $this->staticpages . $slug . '.php';

        // check if the partial file exists, if not set status 404
        if(!file_exists($partial_view))
        {
            $this->response = $this->response->withStatus(404);
            $partial_view = $this->staticpages . 'error' . '.php';
        }

        // set the registy
        $view_registry->set('_content', $partial_view);

		// demo data
        $dataset = [
            'data' => $data, // passing data array to view
            'partial' => 'partial', // passing partial view filename as string to layout
            'debug' => $this->payload->getStatus(),
        ];

        // assign data to the view
        $view->setData($dataset);

        /*
         * check for ajax request and set views accordingly
         */
        if ( $this->is_pjax() )
        {
            // pjax request, set the view only
			// $this->renderView();
            $view->setView('_content');
        } else {
            // regular http request, set view and layout
            $view->setLayout('layout');
            $view->setView('_content');
        }

        $output = $view->__invoke();

        // retun response
        $this->response = $this->response->withHeader('Content-Type', 'text/html');
        $this->response->getBody()->write($output);
    }

}
