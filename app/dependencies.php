<?php

$container = $app->getContainer();

//Register twig component
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../src/view/templates', []);
    //Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};

$container['doctrine'] = function($container) {
    $config = new \Doctrine\DBAL\Configuration();
    $connection = \Doctrine\DBAL\DriverManager::getConnection(
        $container->get('settings')['database'],
        $config
    );
    return $connection;
};

$container['flash'] = function(){
    return new Slim\Flash\Messages();
};

/*
 * REPOSITORIES
 */

$container['bbdd_repository'] = function($container){
    $repository = new \SlimApp\model\Implementations\DoctrineBbddRepository(
        $container->get('doctrine')
    );
    return $repository;
};


/*
 * Servei pel registre
 */
$container['post_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\PostUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};
/*
 * Servei pel login
 */
$container['post_login_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\PostLoginUseCase(
         $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei check usuari; recopilar la informació de l'usuari loguejat
 */

$container['check_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CheckUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei update usuari
 */

$container['update_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\UpdateUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};


/*
 * Servei delete usuari
 */

$container['delete_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\DeleteUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};


/*
 * Servei afegir file
 */

$container['add_file_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\AddFileUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};


/*
 * Servei delete file
 */

$container['delete_file_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\DeleteFileUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei check file
 */

$container['check_file_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CheckFileUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei rename file
 */

$container['rename_file_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\RenameFileUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei add folder
 *
 */
$container['add_folder_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\AddFolderUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};
/*
 * Servei rename folders
 */
$container['rename_folder_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\RenameFolderUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei check folders
 */
$container['check_folder_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CheckFolderUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};
/*
 * Servei delete folder
 */
$container['delete_folder_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\DeleteFolderUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Server share folder
 */

$container['check_email_share_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CheckEmailShareUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei add shared user
 *
 */
$container['add_share_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\AddShareUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};


?>
