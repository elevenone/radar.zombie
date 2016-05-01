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

use Aura\Payload_Interface\PayloadStatus;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Aura\Payload_Interface\PayloadInterface as Payload;

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
}
