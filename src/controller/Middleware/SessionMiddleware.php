<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 19/4/18
 * Time: 18:43
 */

namespace SlimApp\controller\Middleware;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class SessionMiddleware
{

    public function __invoke(Request $request, Response $response, callable $next){
        session_start();
        return $next($request, $response);
    }
}