<?php
/**
 * Static view responder
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
class AuraViewStaticPage implements ResponderAcceptsInterface
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
        // views array
        $this->views = $views;

        // view filepaths from $views array
        // template root path
        $this->template_path = $views['views']['path'];

        // layout view file
        $this->layout = $this->template_path . $views['views']['layout'];

        // staticpages path
        $this->staticpages = $views['views']['staticpages_path'] . '/_';
        // print_r( $this->staticpages );

        // partials path // _content.php // NOT USED HERE FOR NOW
        // $this->partials_path = $views['views']['partials_path'];
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
     * Returns the Responder method to call, based on the Payload status.
     * @return string
     */
    protected function getMethodForPayload()
    {
        if (! $this->payload) {
            return 'noContent';
        }

        $method = str_replace('_', '', strtolower($this->payload->getStatus()));
        return method_exists($this, $method) ? $method : 'unknown';
    }

    /**
     * Returns the Responder
     * @return string
     */
    protected function htmlBody($data)
    {
        // Aura.view setup
        $view_factory = new ViewFactory;
        $view = $view_factory->newInstance();

        // layout
        $layout_registry = $view->getLayoutRegistry();
        $layout_registry->set('layout', $this->layout);

        // views
        $view_registry = $view->getViewRegistry();

        // get slug for partial view
        $slug = $this->request->getAttribute('page');
        $partial_view = $this->staticpages . $slug . '.php';
        $view_registry->set('_content', $partial_view);

        // set data
        $dataset = [
            'data' => $data, // passing data array to view
            'partial' => 'partial', // passing partial view filename as string to layout
        ];

        // assign data to view
        $view->setData($dataset);

        // check for ajax request and set views accordingly
        if ( $this->is_pjax() )
        {
            // pjax request, set the view only
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

    /**
     * Checks for ajax request
     * @return bool
     */
    protected function is_pjax()
    {
        $serverparams = $this->request->getServerParams();

        if(isset( $serverparams['HTTP_X_PJAX'] ) && $serverparams['HTTP_X_PJAX'] == 'true')
        {
            $serverparams = $this->request->getServerParams();
            return TRUE;
        }
        $serverparams = $this->request->getServerParams();
        return FALSE;
    }

    /**
     * Builds a Response for PayloadStatus::SUCCESS.
     */
    protected function success()
    {
        $this->response = $this->response->withStatus(200);
        $this->htmlBody($this->payload->getOutput());
    }

    /**
     * Builds a Response for PayloadStatus::ERROR.
     */
    protected function error($payload)
    {
        $this->response = $this->response->withStatus(500);
        $this->request = $this->request->withAttribute('_view', 'error.php');
        $this->htmlBody($payload);
    }
}
