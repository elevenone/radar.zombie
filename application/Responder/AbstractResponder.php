<?php
/**
 *
 * @category  Responder
 * @package   Application
 * @author    Zsolt Sándor <zsolt.sandor@gmx.com>
 * @copyright 2016 Zsolt Sándor
 *
 */

namespace Application\Responder;

use Aura\View\View;
use Aura\View\ViewFactory as ViewFactory;
use Aura\Payload_Interface\PayloadStatus;
use Aura\Payload_Interface\PayloadInterface as Payload;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Abstract Responder
 */
abstract class AbstractResponder
{
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

    protected function renderView($view)
    {
        $view_factory = new \Aura\View\ViewFactory;
        $this->view = $view_factory->newInstance();
        $this->view->setView($view);
        
        $this->response->withBody($this->view->__invoke());
    }

    /**
     * Builds a Response for PayloadStatus::SUCCESS.
     */
    protected function success()
    {
        $this->response = $this->response->withStatus(200);
        $this->htmlBody($this->payload->getOutput());
    }

    protected function notFound()
    {
        $this->response = $this->response->withStatus(404);
        $this->htmlBody($this->payload->getInput());
    }

    /**
     * Builds a Response for PayloadStatus::ERROR.
     */
    protected function error($payload)
    {
        $this->response = $this->response->withStatus(500);
        $this->request = $this->request->withAttribute('page', 'error.php');
        $this->htmlBody($payload);
    }

    /**
     * Checks for ajax request
     * @return bool
     */
    protected function is_pjax()
    {
        $serverparams = $this->request->getServerParams();

        // $accept  = $request->getHeader('HTTP_X_PJAX');
        print_r($this->request);
        // if(isset( $serverparams['HTTP_X_PJAX'] ) && $serverparams['HTTP_X_PJAX'] == 'true')
        if( $this->request->getHeader('HTTP_X_PJAX') )
        {
            return TRUE;
        }
        return FALSE;
    }
}
