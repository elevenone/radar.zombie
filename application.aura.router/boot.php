<?php
/**
 *
 * Minimal PSR-7 example for Aura Router 3
 *
 */

# Uncomment for debugging.
$whoops = new Whoops\Run;

$whoops->pushHandler(new Whoops\Handler\JsonResponseHandler());
// $whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
$whoops->register();








// Setup PSR-7 request and response objects
$request = \Zend\Diactoros\ServerRequestFactory::fromGlobals();
$response = new \Zend\Diactoros\Response();

// system use
// use Aura\Router\RouterContainer;

$routerContainer = new Aura\Router\RouterContainer();

$map = $routerContainer->getMap();


// print_r ($zzz = new Application\Domain\Core);


// Define routes here
$map->get('blog.read', '/blog/{id}', function ($request, $response) {
    $id = (int) $request->getAttribute('id');
    $response->getBody()->write("You asked for blog entry {$id}.");
  //  $zzz = new Application\Domain\Core;
    return $response;
});

// $map->get('catchall', '{/controller,action,id}')
//    ->defaults([
//        'controller' => 'index',
//        'action' => 'browse',
//        'id' => null,
//    ]);


// Resolve route
$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);



if (! $route) {
    // get the first of the best-available non-matched routes
    $failedRoute = $matcher->getFailedRoute();

    // which matching rule failed?
    switch ($failedRoute->failedRule) {
        case 'Aura\Router\Rule\Allows':
            // 405 METHOD NOT ALLOWED
            // Send the $failedRoute->allows as 'Allow:'
        break;
            case 'Aura\Router\Rule\Accepts':
            // 406 NOT ACCEPTABLE
            break;
        default:
            // 404 NOT FOUND
            $response->getBody()->write(json_encode(["error" => "The requested resource could not be found"]));
            $response = $response->withHeader('Content-Type', 'text/html');
            $response = $response->withStatus(404);
        break;
    }
} else {
    foreach ($route->attributes as $key => $val) {
        $request = $request->withAttribute($key, $val);
    }

    // https://github.com/auraphp/Aura.Router/blob/3.x/docs/getting-started.md
    $callable = $route->handler;
    $response = $callable($request, $response->withStatus(200));

//    $actionClass = $route->handler;
//    $action = new $actionClass();
//    $response = $action($request);

}





use Zend\Diactoros\Response\HtmlResponse;
$response = new HtmlResponse('a');
$response = new HtmlResponse('<strong>b</strong>', 200, [ 'Content-Type' => ['text/html']]);




# Emit the response.
$sapiEmitter = new \Zend\Diactoros\Response\SapiEmitter();
$sapiEmitter->emit($response);












