<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 12/4/18
 * Time: 21:01
 */

namespace SlimApp\controller;


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class PostUserController
{
    private $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function __invoke(Request $request,Response $response)
    {
        try{
            $data = $request->getParsedBody();
            $service = $this->container->get('post_user_use_case');
            $service($data);

        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-Type','text/html')
                ->write($e->getMessage());
        }
        return $response;
    }




}