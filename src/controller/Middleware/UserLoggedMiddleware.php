<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 19/4/18
 * Time: 18:54
 */

namespace SlimApp\controller\Middleware;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserLoggedMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next){
        if(!isset($_SESSION['id'])){
            //NO ESTA LOGGEADO
            //es una redireccion
            return $response->withStatus(302)->withHeader('Location','/');

        }else{
            return $next($request, $response);

        }

    }
}