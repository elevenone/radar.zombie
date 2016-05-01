<?php

namespace Application\Domain;

use Application\Domain\Gateway\Post as PostGateway;
use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus;
use Exception;

class Page
{
    protected $postGateway;

    public function __construct(PostGateway $postGateway)
    {
        $this->postGateway = $postGateway;
    }

    public function __invoke($page)
    {
        try {
            return [
                'success' => true,
                'whoaa' => $input['page'],
                'post' => $this->postGateway->getPostById($page),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
//                 'message' => $input['name'],
                'message' => $e->getMessage(),
            ];
        }
    }
}
