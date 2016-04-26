<?php

namespace Application\Domain;

use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus;

class Index
{
    public function _invoke($input)
    {
        $payload = new Payload();
        return $payload
            ->setStatus(PayloadStatus::SUCCESS)
            ->setOutput([
                'phrase' => 'Hello '. $input['name']
            ]);
//		->defaults(['name' => 'world']);
    }

}


