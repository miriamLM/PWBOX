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
 * Servei per mirar si el email es unic
 */

$container['email_unique'] = function($container){
    $useCase = new \SlimApp\model\UseCase\EmailUniqueUserUseCase(
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

/*
 * Servei get folders of usershared
 *
 */
$container['folders_shared_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\FoldersSharedUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei get info folders shared
 *
 */
$container['check_folders_shared_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CheckFoldersSharedUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei get id_user of folder
 */
$container['check_user_folder_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CheckUserFolderUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei get capacity of user
 */

$container['capacity_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CapacityUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};



/*
 * Servei actualitzar (sumar i restar) capacity of user
 */

$container['actualitzar_capacity_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\ActualitzarCapacityUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei get file_size of the delete file
 */

$container['file_size_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\FileSizeUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};
 /*
  * Servei per eliminar de la taula share si una carpeta compartida s'elimina
  */

$container['delete_share_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\DeleteShareUserUsecase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};
/*
 * Servei per veure els fitxer que han compartit
 */

$container['check_share_file_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CheckShareFilesUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei per veure les folders que han compartit
 */

$container['check_share_folder_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CheckShareFolderUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei perque el que comprteix la folder pugui veure els canviis que fa l'altre
 */

$container['add_inside_share_folder_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\AddInsideShareFolderUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei perque el que comprteix la folder pugui veure els canvis que fa l'altre
 */

$container['add_inside_share_file_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\AddInsideShareFileUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei per tenir info de share depennet de la carpeta
 */

$container['check_share_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CheckShareUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};




/*
 * Servei per guardar notificacion
 */


$container['save_notificacion'] = function($container){
    $useCase = new \SlimApp\model\UseCase\SaveNotificacionUserUsecase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};

/*
 * Servei per agafar la info de un file sabent la id
 */
$container['check_file_id_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CheckFileIdUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};




/*
 * Servei per buscar el folder del file que renombrem
 */

$container['folder_file_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\FolderFileUserUsecase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};



$container['post_verification'] = function($container){
    $useCase = new \SlimApp\model\UseCase\PostVerification(
        $container->get('bbdd_repository')
    );
    return $useCase;
};



$container['exists_user_validation_email'] = function($container){
    $useCase = new \SlimApp\model\UseCase\ExistsUserValidationEmail(
        $container->get('bbdd_repository')
    );
    return $useCase;
};




$container['get_notifications_user'] = function($container){
    $useCase = new \SlimApp\model\UseCase\GetNotificationsUser(
        $container->get('bbdd_repository')
    );
    return $useCase;
};


$container['check_this_folder_user_use_case'] = function($container){
    $useCase = new \SlimApp\model\UseCase\CheckThisFolderUserUseCase(
        $container->get('bbdd_repository')
    );
    return $useCase;
};



$container['get_id_with_email'] = function($container){
    $useCase = new \SlimApp\model\UseCase\GetIdWithEmail(
        $container->get('bbdd_repository')
    );
    return $useCase;
};



?>
