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


$app->get('/','SlimApp\controller\HelloController:landingAction')->add('SlimApp\controller\Middleware\DashboardLoggedMiddleware');

$app->get('/reg','SlimApp\controller\HelloController:registerAction')->add('SlimApp\controller\Middleware\DashboardLoggedMiddleware');

$app->get('/log','SlimApp\controller\HelloController:loginAction')->add('SlimApp\controller\Middleware\DashboardLoggedMiddleware');

$app->get('/prof','SlimApp\controller\HelloController:profileAction')->add('SlimApp\controller\Middleware\UpdateProfileMiddleware');

$app->get('/lp','SlimApp\controller\HelloController:getLandingProfile')->add('SlimApp\controller\Middleware\UserLoggedMiddleware');





$app->post('/registration','SlimApp\controller\HelloController:registerMe');

$app->post('/login','SlimApp\controller\HelloController:loginMe');

$app->post('/prof','SlimApp\controller\HelloController:profileUpdate');



$app->post('/deleteAccount','SlimApp\controller\HelloController:deleteAccount');

$app->post('/deleteFile','SlimApp\controller\HelloController:deleteFileProfile');

$app->post('/renameFile','SlimApp\controller\HelloController:renameFileProfile');

$app->post('/uploadFile','SlimApp\controller\HelloController:uploadFileProfile');

$app->post('/downloadFile','SlimApp\controller\HelloController:downloadFileProfile');

$app->post('/addFolder','SlimApp\controller\HelloController:addFolderProfile');




$app->post('/renameFolder','SlimApp\controller\HelloController:renameFolderProfile');

$app->post('/deleteFolder','SlimApp\controller\HelloController:deleteFolderProfile');

$app->post('/downloadFolder','SlimApp\controller\HelloController:downloadFolderProfile');




$app->post('/lp/{id}','SlimApp\controller\HelloController:printFileFolder');

$app->post('/lpp/{id}','SlimApp\controller\HelloController:FileFolder');


$app->post('/shareFolder','SlimApp\controller\HelloController:shareFolder');

$app->post('/sharedFolders','SlimApp\controller\HelloController:sharedFolders');


$app->post('/logout','SlimApp\controller\HelloController:logout');



//->add('SlimApp\controller\Middleware\TestMiddleware') // afegir mes middlewares

    //Si el template el poso dins d'una carpeta per exemple test
    //osigui view-> tindria una carpeta test// i alla poso el hello.twig
    //return $this->view->render($response, 'test/hello.twig', ['name'=> $name]);

?>
