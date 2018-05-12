<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 10/05/2018
 * Time: 10:04
 */


namespace SlimApp\controller\Middleware;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class DashboardLoggedMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next){
        if(isset($_SESSION['id'])){
            //ESTA LOGGEADO
            //es una redireccion
            return $response->withStatus(302)->withHeader('Location','/lp');

        }else{
            return $next($request, $response);

        }

    }
}