<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 12/5/18
 * Time: 16:09
 */

namespace SlimApp\controller\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;

class UpdateProfileMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next){
        if(!isset($_SESSION['id'])){
            //NO ESTA LOGGEADO
            //es una redireccion



           return $response->withStatus(403)
                ->withHeader("Status: 403 Forbidden", 'text/html')
                ->write('HTTP/1.0 403 Forbidden');


        }else{
            return $next($request, $response);

        }

    }

}
