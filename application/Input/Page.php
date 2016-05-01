<?php
namespace Application\Input;

use Psr\Http\Message\ServerRequestInterface as Request;

class Page
{
    public function __invoke(Request $request)
    {
        return [$request->getAttribute('page')];
    }
}



