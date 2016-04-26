<?php
namespace Application\Input;

use Psr\Http\Message\ServerRequestInterface as Request;

class NoneExpected
{
    public function __invoke(Request $request)
    {
        return [];
    }
}
