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
        $id = $_SESSION['id'];
        $id_folder = 0;
        $servei = $this->container->get('check_file_user_use_case');

        /**
         *Tota la informació dels fitxers
         */
        $info = $servei();

        $img = "/assets/img/file.png";

        $num_items = sizeof($info);

        $array = [];
        for($i=0;$i<$num_items;$i++){
            $item = new Item($info[$i]['name'],$img,$id,$info[$i]['id'],0);
            array_push($array,$item);
        }


        return $this->container
            ->get('view')
            ->render($response, 'dashboard.twig', ['item' => $array]);

    }



    public function registerMe(Request $request, Response $response)
    {

        $data = $request->getParsedBody();
        $servei = $this->container->get('post_user_use_case');
        $errors = $this->validacions($data, 0);
        if ($errors[0] == "" && $errors[1] == "" && $errors[2] == "" && $errors[3] == "" && $errors[4] == "") {
            /*
             * Guarda informació d'usuari a la BBDD
             */
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

    public function postLandingProfile(Request $request, Response $response)
    {
        try {

            /**
             * Upload folder
             */
            if(isset($_POST['folder'])){
                $id = $_SESSION['id'];
                $servei = $this->container->get('folder_user_use_case');
                $servei($id);

            }

        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }

    public function deleteAccount(Request $request, Response $response){
        /**
         * Delete Account
         */

        if(isset($_POST['cancel'])){
            return $response->withStatus(302)->withHeader('Location', '/lp');
        }
        if(isset($_POST['continue'])){

            $id = $_SESSION['id'];
            $servei= $this->container->get('delete_user_use_case');
            $stmt = $servei($id);
            if($stmt->execute()){
                echo "<script>alert(\"Record Deleted\");location.href='/'</script>";
                $_SESSION['id']= null;
                //$response->getBody()->write("Warning");
                //return $response->withStatus(302)->withHeader('Location', '/');
            }
        }
    }

    /**
     * ROLE ADMIN
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
            $info = $servei($file_name,$file_new_name);

            $num_items = sizeof($info);

            $img = "/assets/img/file.png";

            $array = [];
            for ($i = 0; $i < $num_items; $i++) {
                $item = new Item($info[$i]['name'], $img, $info[$i]['id_user'], $info[$i]['id'], 0);
                array_push($array, $item);
            }

             $this->container
                ->get('view')
                ->render($response, 'dashboard.twig', ['item' => $array]);

            return $response->withStatus(302)->withHeader('Location', '/lp');

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
            $info = $servei($file_id);

            $num_items = sizeof($info);



            $img = "/assets/img/file.png";

            $array = [];
            for($i=0;$i<$num_items;$i++){
                $item = new Item($info[$i]['name'],$img,$info[$i]['id_user'],$info[$i]['id'],0);
                array_push($array,$item);
            }

            $this->container
                ->get('view')
                ->render($response, 'dashboard.twig', ['item' => $array]);

            return $response->withStatus(302)->withHeader('Location', '/lp');

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
                    $id_folder = 0;
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
                        $item = new Item($info[1][$i]['name'], $img, $id, $info[1][$i]['id'], 0);
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

    public function addFolderProfile(Request $request, Response $response)
    {
        if(isset($_POST['addSubmit']))
        {

            $id = $_SESSION['id'];
            $id_parent= "0";
            $foldern = $request->getParsedBody();
            $folder_name = $foldern['nameFolder'];
            $servei = $this->container->get('add_folder_user_use_case');
            $info = $servei((int)$id,$folder_name,$id_parent);

            $num_folders = (int)$info[0];
            //var_dump($num_folders);

            $img = "/assets/img/folder.jpg";

            //var_dump($info[1][1]['name']);

            $array = [];
            for ($i = 0; $i < $num_folders; $i++) {

                $folder = new Folder($info[1][$i]['name'], $img, $id, $info[1][$i]['id'], 0);
                var_dump($folder);
                array_push($array, $folder);

            }
            $this->container
                ->get('view')
                ->render($response, 'dashboard.twig', ['folder' => $array]);


            //return $response->withStatus(302)->withHeader('Location', '/lp');
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





    public function validacions($rawData, $opcio)
    {
        $usernameErr = "";
        $emailErr = "";
        $birthErr = "";
        $pswErr = "";
        $confirmpswErr = "";
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
                return [$usernameErr, $emailErr, $birthErr, $pswErr, $confirmpswErr];
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
