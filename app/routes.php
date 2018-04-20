<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->add('SlimApp\controller\Middleware\SessionMiddleware');

$app->get(
    '/login',
    'SlimApp\controller\HelloController:loginMe'
)->add('SlimApp\controller\Middleware\UserLoggedMiddleware');



/*$app->get(
  '/hello/{name}',
  'SlimApp\controller\HelloController:indexAction'
 )->add('SlimApp\controller\Middleware\UserLoggedMiddleware');

$app->get('/user','SlimApp\controller\HelloController:indexaAction');*/

/*$app->post(
    '/user',
    'SlimApp\controller\PostUserController'
);*/


$app->get('/','SlimApp\controller\HelloController:landingAction');

$app->get('/reg','SlimApp\controller\HelloController:registerAction');

$app->get('/log','SlimApp\controller\HelloController:loginAction');

$app->get('/prof','SlimApp\controller\HelloController:profileAction')->add('SlimApp\controller\Middleware\UserLoggedMiddleware');

$app->get('/lp','SlimApp\controller\HelloController:getLandingProfile');





$app->post('/registration','SlimApp\controller\HelloController:registerMe');

$app->post('/login','SlimApp\controller\HelloController:loginMe');

$app->post('/profileUpdate','SlimApp\controller\HelloController:profileUpdate');

$app->post('/lp','SlimApp\controller\HelloController:postLandingProfile');

$app->post('/delete','SlimApp\controller\HelloController:deleteFileProfile');

$app->post('/renameFile','SlimApp\controller\HelloController:renameFileProfile');

$app->post('/uploadFile','SlimApp\controller\HelloController:uploadFileProfile');

$app->post('/downloadFile','SlimApp\controller\HelloController:downloadFileProfile');



//->add('SlimApp\controller\Middleware\TestMiddleware') // afegir mes middlewares

    //Si el template el poso dins d'una carpeta per exemple test
    //osigui view-> tindria una carpeta test// i alla poso el hello.twig
    //return $this->view->render($response, 'test/hello.twig', ['name'=> $name]);

?>
