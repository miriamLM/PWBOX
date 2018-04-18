<?php

namespace SlimApp\controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class TestMiddleware{

  public function __invoke(Request $request, Response $response, callable $next){
    $response->getBody()->write('BEFORE');
    $response = $next($request, $response);
    $response->getBody()->write('AFTER');

    return $response;
  }

}

 ?>
