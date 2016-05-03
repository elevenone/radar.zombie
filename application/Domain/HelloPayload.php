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
    public function __invoke(array $input)
    {
        $payload = new Payload();
        return $payload
            ->setStatus(PayloadStatus::SUCCESS)
            ->setOutput([
                'message' => 'input is a : ' . $input['page']
            ]);
    }
}

