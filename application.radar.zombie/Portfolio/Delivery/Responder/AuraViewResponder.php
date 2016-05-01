<?php
/**
 *
 * This file is part of Radar.zombie
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 */
namespace Portfolio\Delivery\Responder;

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
    public function __construct($viewDir, $views) // $viewDir
    {
//		if ($views) {
			// print_r($views);
//			$this->$views = $views;
//		}
        if (is_array($views))
        {
			// $this->$views = $views;
//			$obj = new \stdClass();
//			array_to_obj($views, $obj->$key);
        }
        else
        {
//			$obj = $views;
			// $this->$views = $views;
        }

        $this->viewDir = $viewDir;
//		echo $this->viewDir;
		
  //      if (file_exists($this->viewDir . '/' . $view)) {
	//		echo '78978987<br/><br/><br/>';
	//		echo $this->viewDir . '/' . 'layout.php';
            // include $this->viewDir . '/' . $view;
      //  }
		
    }

    /**
     * SetRules
     *
     * @param array $rules Map of rules
     *
     * @return mixed
     *
     * @access public
     */
    public function setViews()
    {
        // echo 'set views';
        //$this->rules = $rules;
        //return $this;

        if (is_array($views))
        {
            // 00 vars
            $folder = Arr::path($views, 'views.path');
            //$layout = Arr::path($views, 'views.layout');
            //$error = Arr::path($views, 'views.error');
            //$partials = Arr::path($views, 'views.partials');
            //$static = Arr::path($views, 'views.static');

            // 01 main template
            //$layout_registry->set('layout', APPPATH . $folder . DIRECTORY_SEPARATOR . $layout);

            // error template
            //$layout_registry->set('error',  APPPATH . $folder . DIRECTORY_SEPARATOR . $error);

            // 02 sub templates
//            foreach ($partials as $key => $value)
//            {
//                $view_registry->set( $key,  APPPATH . $folder . DIRECTORY_SEPARATOR . $value );
                // echo $key,  APPPATH . $folder . DIRECTORY_SEPARATOR . $value . '<br/>' ;
//            }

            // 03 sub templates
//            foreach ($static as $key => $value)
//            {
//                $view_registry->set( $key,  APPPATH . $folder . DIRECTORY_SEPARATOR . $value );
//            }

        }

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

        // main view
        $layout_registry = $view->getLayoutRegistry();
        $layout_registry->set('layout', $this->viewDir . '/layout.php');

        // partial view
        $view_registry = $view->getViewRegistry();
        $partial = $this->request->getAttribute('_content');
        $view_registry->set('_content', $this->viewDir . '/_' . $partial); // set partial view main content file as dynamic partial

        $dataset = [
            'data' => $data, // passing data array to view
            'partial' => $partial, // passing partial view filename as string to layout
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
