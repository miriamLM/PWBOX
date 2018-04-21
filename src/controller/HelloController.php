<?php
namespace SlimApp\controller;

use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
//use PHPUnit\Runner\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Dflydev\FigCookies\FigRequestCookies;
use SlimApp\model\Folder;
use SlimApp\model\Item;


class HelloController
{

    protected $container;
    const DATA = 'Y/m/d';
    const FOLDER_IMAGE = '/assets/img/folder.jpg';

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function indexaAction(Request $request, Response $response)
    {
        $messages = $this->container->get('flash')->getMessages();
        $userRegisteredMessages = isset($messages['user_register']) ? $messages['user_register'] : [];

        return $this->container->get('view')
            ->render($response, 'register.twig', ['messages' => $userRegisteredMessages]);
    }

    public function registeraAction(Request $request, Response $response, array $args)
    {
        /*if (!isset($_SESSION['counter'])) {
            $_SESSION['counter'] = 1;

        } else {
            $_SESSION['counter']++;

        }*/

        $cookie = FigRequestCookies::get($request, 'advice', 0);

        if (empty($cookie)) {
            $response = FigResponseCookies::set($response, SetCookie::create('advice')
                ->withValue(1)
                ->withDomain('slimapp.test')
                ->withPath('/')
            );
        }


        $name = $request->getAttribute('name');
        return $this->container
            ->get('view')
            ->render($response, 'hello.twig',
                ['name' => $name, 'counter' => $_SESSION['counter'], 'advice' => $cookie->getValue()]);
    }

    public function indexAction(Request $request, Response $response, array $args)
    {
        $name = $args['name'];
        return $this->container
            ->get('view')
            ->render($response, 'hello.twig', ['name' => $name]);
    }



    public function landingAction(Request $request, Response $response)
    {
        try {
            return $this->container
                ->get('view')
                ->render($response, 'landing.twig');
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }

    public function registerAction(Request $request, Response $response)
    {

        $errors = ["", "", "", "", ""];
        try {
            return $this->container
                ->get('view')
                ->render($response, 'registration.twig', ['errors' => $errors]);
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }

    }

    public function loginAction(Request $request, Response $response)
    {
        $errors = ["", ""];
        try {
            return $this->container
                ->get('view')
                ->render($response, 'login.twig', ['errors' => $errors]);
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }

    }

    public function profileAction(Request $request, Response $response)
    {
        $errors = ["", "", "", "", ""];
        $id = $_SESSION['id'];
        var_dump($id);
        $servei = $this->container->get('check_user_use_case');
        $user = $servei($id);
        var_dump($user[0]);
        try {
            return $this->container
                ->get('view')
                ->render($response, 'profile.twig',['user' => $user[0]],['errors' => $errors]);
        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }

    public function getLandingProfile(Request $request, Response $response)
    {
        /*
         * files
         */
        $id = $_SESSION['id'];
        $id_folder = 0;
        $servei = $this->container->get('check_file_user_use_case');

        /**
         *Tota la informació dels fitxers
         */
        $info = $servei($id_folder,$id);

        $img = "/assets/img/file.png";

        $num_items = sizeof($info);

        $array = [];
        for($i=0;$i<$num_items;$i++){
            $item = new Item($info[$i]['name'],$img,$id,$info[$i]['id'],0);
            array_push($array,$item);
        }

        /*
         * folders
         */
        $servei2 = $this->container->get('check_folder_user_use_case');

        /**
         *Tota la informació dels fitxers
         */
        $info2 = $servei2($id_folder,$id);

        $img2 = "/assets/img/folder.png";

        $num_folders = sizeof($info2);


        $array2 = [];
        for($i=0;$i<$num_folders;$i++){
            $folder = new Folder($info2[$i]['name'],$img2,$id,$info2[$i]['id'],0);
            array_push($array2,$folder);
        }

        return $this->container
            ->get('view')
            ->render($response, 'dashboard.twig', ['item' => $array , 'folder' => $array2]);

    }


    public function logout(Request $request, Response $response){
        $_SESSION['id'] = null;
        return $response->withStatus(302)->withHeader('Location','/');
    }



    public function registerMe(Request $request, Response $response)
    {

        $data = $request->getParsedBody();
        $servei = $this->container->get('post_user_use_case');
        $errors = $this->validacions($data, 0);
        if ($errors[0] == "" && $errors[1] == "" && $errors[2] == "" && $errors[3] == "" && $errors[4] == "" && $errors[5] == "") {
            /*
             * Guarda informació d'usuari a la BBDD
             */
            echo "NO ERROR\n";
            var_dump($_FILES["myfile"]);
            if(empty($_FILES["myfile"])){
                $data["myfile"] = "/assets/img/user.png";
                echo"-------------------";
                var_dump($data);
            }else{
                $name_img= $_FILES["myfile"]["name"];
                $destination = "/assets/img/";
                $data["myfile"] = "/assets/img/folder.png";
                $test = move_uploaded_file($name_img,$destination);
                var_dump($test);
            }


            $servei($data);
            return $response->withStatus(302)->withHeader('Location','/log');

        }else {

            return $this->container
                ->get('view')
                ->render($response, 'registration.twig', ['errors' => $errors]);
        }

    }

    public function loginMe(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $servei = $this->container->get('post_login_use_case');
        $errors = $this->validacions($data, 1);
        if ($errors[0] == "" && $errors[1] == "") {
            $id = $servei($data);
            if (!empty($id)) {
                echo("EXISTE");
                /**
                 * landingProfile -> Landing, quan el usuari s'hagi pogut loguejar
                 *  **/
                $_SESSION['id'] = $id;
                return $response->withStatus(302)->withHeader('Location', '/lp');
            } else {
                echo "<script>alert(\"NO EXISTE USUARIO.\");location.href='/log'</script>";

            }
        } else {
            return $this->container
                ->get('view')
                ->render($response, 'login.twig', ['errors' => $errors]);
        }

    }

    public function profileUpdate(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $servei = $this->container->get('update_user_use_case');
        $errors = $this->validacions($data, 0);
        if ($errors[0] == "" && $errors[1] == "" && $errors[2] == "" && $errors[3] == "" && $errors[4] == "") {
            $servei($data);
            return $response->withStatus(302)->withHeader('Location', '/prof');
        }
    }



    public function deleteAccount(Request $request, Response $response){
        /**
         * Delete Account
         */

        if(isset($_POST['cancel'])){
            return $response->withStatus(302)->withHeader('Location', '/prof');
        }
        if(isset($_POST['continue'])){

            $id = $_SESSION['id'];
            $servei= $this->container->get('delete_user_use_case');
            $stmt = $servei($id);
            if($stmt->execute()){
                echo "<script>alert(\"Record Deleted\");location.href='/'</script>";
                $_SESSION['id']= null;
                $response->getBody()->write("Warning");
                return $response->withStatus(302)->withHeader('Location', '/');
            }
        }
    }

    /**
     * ROLE ADMIN
     */


    /**
     * FILE
     */
    public function renameFileProfile(Request $request, Response $response)
    {
        /**
         * Rename file
         */

        if (isset($_POST['submit'])) {
            var_dump($request->getParsedBody());
            $file = $request->getParsedBody();
            $file_name = $file['file_name'];
            $file_new_name = $file['titleFile'];
            $servei = $this->container->get('rename_file_user_use_case');

            $servei($file_name,$file_new_name);

            $response->withStatus(302)->withHeader('Location', '/insideFolder');

            return  $response->withStatus(302)->withHeader('Location', '/lp');


        }
    }

    public function deleteFileProfile(Request $request, Response $response){
        /**
         * Delete file
         */
        if(isset($_POST['deleteFile'])){
            $file = $request->getParsedBody();
            $file_id = $file['file_id'];
            $servei = $this->container->get('delete_file_user_use_case');
            $servei($file_id);


            $response->withStatus(302)->withHeader('Location', '/insideFolder');

            return  $response->withStatus(302)->withHeader('Location', '/lp');


        }

    }

    public function uploadFileProfile(Request $request, Response $response){

        /**
         * Upload file
         */
        if(isset($_POST['uploadSubmit'])){
            $file = $_FILES['addFile'];
            $filesize = $file['size'];
            /**
             * El size del fitxer no pot superar el 2MB
             */
            if($filesize > 2000000){
                //$message='Error, File Size Superior of 2MB';
                echo "<script>alert(\"Error, File Size Superior of 2MB\");location.href='/lp'</script>";

                /*return $response->withStatus(302)->withHeader('Location', '/lp')
                                ->getBody()->write($message);*/

            }else{

                $allowed =  array('gif','png' ,'jpg','pdf','md','txt');
                $filename = $_FILES['addFile']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);


                if(!in_array($ext,$allowed) ) {
                    /**
                     * Aqui deberia Salir un Error WARNING
                     * i despues creo que se deberia ridireccionar al dashboard otra vez
                     */

                    echo "<script>alert(\"Error Type File Incorrect:Types Availables: 1.PDF (.pdf) 2.JPG, PNG and GIF  3.MARKDOWN (.md) 4.TEXT (.txt)\");location.href='/lp'</script>";

                    /*$message='Error Type File Incorrect:Types Availables: 1.PDF (.pdf) 2.JPG, PNG and GIF  3.MARKDOWN (.md) 4.TEXT (.txt)';
                    return $response->withStatus(302)->withHeader('Location', '/lp')
                        ->getBody()->write($message);*/
                }else {



                    $id = $_SESSION['id'];

                    $item = $request->getParsedBody();
                    $id_folder = $item['uploadSubmit'];

                    if($id_folder == ''){
                        $id_folder=0;
                    }

                    $servei = $this->container->get('add_file_user_use_case');

                    /**
                     * Et retorna el número de fitxers
                     * i tota la informació dels fitxers
                     */
                    $info = $servei($file, $id, $id_folder);


                    // Pq mho guardava com a string i ho vui amb int
                    $num_items = (int)$info[0];
                    // var_dump($num_items);
                    $img = "/assets/img/file.png";


                    $array = [];
                    for ($i = 0; $i < $num_items; $i++) {
                        $item = new Item($info[1][$i]['name'], $img, $id, $info[1][$i]['id'], $id_folder);
                        array_push($array, $item);
                    }


                    $this->container
                        ->get('view')
                        ->render($response, 'dashboard.twig', ['item' => $array]);

                    return $response->withStatus(302)->withHeader('Location', '/lp');
                }
            }

        }

    }


    /**
     *FOLDER
     */

    public function addFolderProfile(Request $request, Response $response)
    {
        if(isset($_POST['addSubmit'])) {


            $id = $_SESSION['id'];

            $foldern = $request->getParsedBody();
            $folder_name = $foldern['nameFolder'];
            $id_parent = $foldern['addSubmit'];

            if($id_parent == ''){
                $id_parent=0;
            }

            $servei = $this->container->get('add_folder_user_use_case');
            $info = $servei((int)$id,$folder_name,$id_parent);

            $num_folders = (int)$info[0];

            $img = "/assets/img/folder.png";


            $array = [];
            for ($i = 0; $i < $num_folders -1; $i++) {

                    $folder = new Folder($info[1][$i]['name'], $img, $id, $info[1][$i]['id'], $id_parent);
                    array_push($array, $folder);

            }

              $this->container
                ->get('view')
                ->render($response, 'dashboard.twig', ['folder' => $array]);

                return $response->withStatus(302)->withHeader('Location', '/lp');

        }
    }

    public  function printFileFolder(Request $request, Response $response){


        $fold = $request->getParsedBody();

        var_dump($fold);
        $id = $_SESSION['id'];
        var_dump($id);
        //hacemos un servicio para saber el id_user de la carpeta
        $servei_id_user = $this->container->get('check_user_folder_use_case');
        $info_user = $servei_id_user($fold['folder_id']);
        $id_user =$info_user[0];
        //var_dump();

        /*if ($id_user != $_SESSION['id']){
            echo "NO SON IGUALES";
            $_SESSION['id_owner'] = $id_user;
            $id = $_SESSION['id_owner'];
            var_dump($id_user);
            var_dump($id);
        }*/



        $id_folder = $fold['folder_id'];


        $servei = $this->container->get('check_file_user_use_case');

        /**
         *Tota la informació dels fitxers
         */
        $info = $servei($id_folder,$id);

        $img = "/assets/img/file.png";

        $num_items = sizeof($info);

        $array = [];
        for($i=0;$i<$num_items;$i++){
            $item = new Item($info[$i]['name'],$img,$id,$info[$i]['id'],$id_folder);
            array_push($array,$item);
        }
        /*
         * folders
         */
        $servei2 = $this->container->get('check_folder_user_use_case');

        /**
         *Tota la informació de la carpeta
         */
        $info2 = $servei2($id_folder,$id);

        $img2 = "/assets/img/folder.png";

        $num_folders = sizeof($info2);


        $array2 = [];
        for($i=0;$i<$num_folders;$i++){
            $folder = new Folder($info2[$i]['name'],$img2,$id,$info2[$i]['id'],$id_folder);
            array_push($array2,$folder);
        }

        $_SESSION['id_owner']= null;




        return $this->container
            ->get('view')
            ->render($response, 'dashboard.twig', ['folder' => $array2,'item' => $array ,"idParent" => $id_folder , "idFolder" => $id_folder]);



    }

    public function renameFolderProfile(Request $request, Response $response)
    {
        /**
         * Rename file
         */

        if (isset($_POST['submit'])) {
            $folder = $request->getParsedBody();

            $folder_name = $folder['folder_name'];
            $folder_new_name = $folder['titleFolder'];

            $servei = $this->container->get('rename_folder_user_use_case');

            $servei($folder_name,$folder_new_name);


            return  $response->withStatus(302)->withHeader('Location', '/lp');


        }
    }

    public function deleteFolderProfile(Request $request, Response $response){
        /**
         * Delete file
         */
        if(isset($_POST['deleteFolder'])){
            $folder = $request->getParsedBody();
            $folder_id = $folder['folder_id'];

            var_dump($folder);
            $servei = $this->container->get('delete_folder_user_use_case');
            $servei($folder_id);



            return  $response->withStatus(302)->withHeader('Location', '/lp');


        }

    }




    /**
     * ROLE READER
     */

    public function downloadFileProfile(Request $request, Response $response){
         if(isset($_POST['downloadFile'])){
             $file = $request->getParsedBody();
             $file_name = $file['file_name'];
             if(file_exists($file_name)){
                 flush();
                 readfile($file_name);
                 exit;
             }
         }
    }


    /**
     *SHARED -> cuando quieres compartir una carpeta con otro usuario
     */

    public function shareFolder(Request $request, Response $response){
        if(isset($_POST['sharesubmit'])){
            $folder = $request->getParsedBody();
            $mail= $folder['mail'];
            $id_owner = $_SESSION['id'];
            $id_folder = $folder['folder_id'];
            $type = $folder['checkbox'];
            $servei = $this->container->get('check_email_share_user_use_case');
            $id_usershared = $servei($mail);
            /**
             * Si el email no existe -> el id sera 0 el que me devuelve
             * Saltara error
             */
            if($id_usershared == 0){
                echo "<script>alert(\"ERROR\");location.href='/lp'</script>";
            }else{
                if($type == 'on'){
                    $type = 'admin';
                }else{
                    $type = 'reader';
                }
                $servei = $this->container->get('add_share_user_use_case');
                $servei($id_owner,$id_usershared,$id_folder,$type);
                return $response->withStatus(302)->withHeader('Location', '/lp');
            }
        }
    }

    /**
     *Cuando entras en la carpeta share
     */

    public function sharedFolders(Request $request, Response $response){
        if(isset($_POST{'sharedFolder'})){
            $_SESSION['id_owner'] = null;
            $id_usershared = $_SESSION['id'];
            $servei = $this->container->get('folders_shared_user_use_case');
            $folders = $servei($id_usershared);


            $num_folders = sizeof($folders);


            $array = [];
            $img2 = "/assets/img/folder.png";
            $id_parent = 1; // si esta en share, necesito la id_parent?

            for($i=0; $i<$num_folders;$i++){
                $shared = $folders[$i]['id_folder'];
                $servei4 = $this->container->get('check_folders_shared_user_use_case');

                $folder_shared = $servei4($shared);

                $folder = new Folder($folder_shared[0]['name'],$img2,$id_usershared,$folder_shared[0]['id'],1);
                array_push($array,$folder);
            }

            return $this->container
                ->get('view')
                ->render($response, 'dashboard.twig', ['folder' => $array]);


        }
    }


    public function validacions($rawData, $opcio)
    {
        $usernameErr = "";
        $emailErr = "";
        $birthErr = "";
        $pswErr = "";
        $confirmpswErr = "";
        $imageErr="";
        switch ($opcio) {
            /*REGISTRE*/
            case 0:
                if (empty($rawData["username"])) {
                    $usernameErr = "Username cannot be empty";
                } else {
                    if (strlen($rawData["username"]) > 20) {
                        $usernameErr = "Length must be less than 20 characters";
                    } else {
                        $pattern = "/[`'\"~!@# $*()<>,:;{}\|]/";
                        if (preg_match($pattern, $rawData["username"])) {
                            $usernameErr = "Only can contain alphanumeric characters";
                        }
                    }
                }
                if (empty($rawData["email"])) {
                    $emailErr = "Email cannot be empty";
                } else {
                    if (!filter_var($rawData["email"], FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid email format";
                    }
                }
                if (empty($rawData["birthdate"])) {
                    $birthErr = "Birth Date cannot be empty";
                } else {
                    var_dump($rawData["birthdate"]);
                    if (!preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/",  $rawData["birthdate"])){
                        $birthErr = "Birthdate wrong format";
                    }
                }
                if (empty($rawData["psw"])) {
                    $pswErr = "Password is required";
                } else {
                    if (strlen($rawData["psw"]) < 6 || strlen($rawData["psw"]) > 12) {
                        $pswErr = "Length must be between 6 and 12 characters";
                    } else {
                        if (!preg_match('/[0-9]/', $rawData["psw"])) {
                            $pswErr = "At least one number";
                        } else {
                            if (!preg_match('/[A-Z]/', $rawData["psw"])) {
                                $pswErr = "At least one upper case character";
                            }
                        }
                    }
                }
                if (empty($rawData["confirmpsw"])) {
                    $confirmpswErr = "Confirm Password is required";
                } else {
                    if ($rawData["confirmpsw"] != $rawData["psw"]) {
                        $confirmpswErr = "Incorrect Password";
                    }
                }
                /*image*/

                if($_FILES["myfile"]["error"]>0){
                    echo"error";
                    var_dump($_FILES["myfile"]["error"]);
                    if($_FILES["myfile"]["error"]==2){
                        $imageErr = "size error";
                    }
                }else{
                    echo "NO ERROR IMG";
                    //var_dump($_FILES["myfile"]);
                }


                return [$usernameErr, $emailErr, $birthErr, $pswErr, $confirmpswErr, $imageErr];
                break;
            /*LOGIN*/
            case 1:
                if (empty($rawData["emailuser"])) {
                    $emailErr = "Email cannot be empty";
                } else {
                    $pattern = "/[`'\"~!@# $*()<>,:;{}\|]/";
                    if ((!filter_var($rawData["emailuser"], FILTER_VALIDATE_EMAIL))
                        && ((strlen($rawData["emailuser"]) > 20) || (preg_match($pattern, $rawData["emailuser"]))) ) {
                        $emailErr = "Error Email/Username";
                    }
                }
                if (empty($rawData["psw"])) {
                    $pswErr = "Password is required";
                } else {
                    if (strlen($rawData["psw"]) < 6 || strlen($rawData["psw"]) > 12) {
                        $pswErr = "Length must be between 6 and 12 characters";
                    } else {
                        if (!preg_match('/[0-9]/', $rawData["psw"])) {
                            $pswErr = "At least one number";
                        } else {
                            if (!preg_match('/[A-Z]/', $rawData["psw"])) {
                                $pswErr = "At least one upper case character";
                            }
                        }
                    }
                }
                return [$emailErr, $pswErr];
                break;
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}




  /*public function __invoke(Request $request, Response $response, array $args){
    $name = $args['name'];
    return $this->container
          ->get('view')
          ->render($response, 'hello.twig', ['name' => $name]);
  }*/





 ?>
