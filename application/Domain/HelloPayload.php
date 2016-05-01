<?php

namespace Application\Domain;

use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus;

class HelloPayload
{
    /**
     * @param array $input
     * @return Payload
     */
    public function __invoke()
    {
        $payload = new Payload();
        return $payload
            ->setStatus(PayloadStatus::SUCCESS)
            ->setOutput([
                'message' => 'input is : ' . $input['page']
            ]);
    }
}

