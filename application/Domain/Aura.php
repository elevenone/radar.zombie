<?php

namespace Application\Domain;

use Aura\Payload\Payload as Payload;
use Aura\Payload_Interface\PayloadStatus as PayloadStatus;

class Aura
{
    public function __construct()
    {
        $this->payload = new Payload();
    }

    public function __invoke(array $input)
    {

        $page = 'index';
        if (!empty($input['page'])) {
            $page = $input['page'];
        }

        return $this->payload
            ->setStatus('SUCCESS')
            ->setOutput([
                'hello' => $page,
            ]);
    }

}


