<?php

namespace Application\Domain;

use Aura\Payload\Payload as Payload;
// use Aura\Payload_Interface\PayloadStatus as PayloadStatus;

class DomainTest
{
    public function __construct()
    {
        $this->payload = new Payload();
    }

    public function __invoke(array $input)
    {
		print_r( $input );

		$name = 'world';
		if (!empty($input['name'])) {
		    $name = $input['name'];
		}

		return $this->payload
		    ->setStatus('SUCCESS')
		    ->setOutput([
		        'hello' => $name,
		    ]);

    }
}


