<?php

namespace Application\Domain;

use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus;

class Hello
{
    /**
     * @param array $input
     * @return Payload
     */
    public function __invoke(array $input)
    {
//        $payload = new Payload();
//        return $payload
//            ->setStatus(PayloadStatus::SUCCESS)
//            ->setOutput([
//                'phrase' => 'Hello from ' . __CLASS__ . '   ' . $input['name'] . ' and viewPath = ' . $viewPath
//            ]);
                
                return [
                    'success' => true,
                    'invoices' => 'asasas',
                ];
    }
}



// from http://nextat.co.jp/staff/archives/150



