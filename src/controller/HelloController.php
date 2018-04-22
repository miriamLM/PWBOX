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
use SlimApp\model\FolderShared;
use SlimApp\model\Item;
use SlimApp\model\ItemShared;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;


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
        $ok = 1;
        $advertencia=1;
        $errors = ["", ""];
        try {
            return $this->container
                ->get('view')
                ->render($response, 'login.twig', ['errors' => $errors,'ok' => $ok,'advertencia'=> $advertencia]);
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
        /*
         * Check user para la imagen
         */
        $servei_user = $this->container->get('check_user_use_case');
        $info_user = $servei_user($id);
        $image = $info_user[0]['image'];
        //var_dump($image);



        /*
         * files
         */

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
            ->render($response, 'dashboard.twig', ['item' => $array , 'folder' => $array2,'image' => $image]);

    }


    public function logout(Request $request, Response $response){
        $_SESSION['id'] = null;
        return $response->withStatus(302)->withHeader('Location','/');
    }



    public function registerMe(Request $request, Response $response)
    {

        $data = $request->getParsedBody();
        $servei = $this->container->get('post_user_use_case');

        $servei2 = $this->container->get('exists_user_validation_email');

        /**
         * Capacidad 1GB para cada usuario registrado -> 1^9 bytes
         */
        $capacity = 1000000000;
        $errors = $this->validacions($data, 0);
        if ($errors[0] == "" && $errors[1] == "" && $errors[2] == "" && $errors[3] == "" && $errors[4] == "" && $errors[5] == "") {
            /*
             * Guarda informació d'usuari a la BBDD
             */
            echo "NO ERROR\n";
            var_dump($_FILES["myfile"]);
            if($_FILES['myfile']['name'] == ""){
                $data["myfile"] = "user.png";
            }else{

                $name_img= $_FILES["myfile"]["name"];
                //$destination ="assets/img/".$data["name"];
                $destination ="assets/img/".$data["username"].".".$_FILES["myfile"]["name"];
                $data["myfile"] = $data["username"].".".$_FILES["myfile"]["name"];
                $name= $data["username"].".".$_FILES["myfile"]["tmp_name"];
                $test = move_uploaded_file($_FILES['myfile']['tmp_name'],$destination);

            }
            $servei($data,$capacity,0);


            $id = $servei2($data);


            //NECESSITO SABER EL ID DE L'USUARI QUE ACABO DE GUARDAR A LA BBDD PER AIXI AFEGIRLO AL LINK EMAIL


            $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
                ->setUsername ('kkaarme11@gmail.com')
                ->setPassword ('carmexuxigemma');

            $mailer = (new Swift_Mailer($transport));

            $message = (new Swift_Message('HOLA'))
                ->setFrom('send@example.com')
                ->setTo($data['email'])
                ->setBody(
                    '<html>
            <head>
            <title>Activate your account</title>
            </head>
            <body>
            <h1>Validate your account {{ id }}</h1>
            <a href="http://slimapp.test/changeValidation/'.$id.'">Click here to validate your account!</a>
            </body>
            </html>'
                )
            ;
            $mailer->send($message);

            //return $this->render();


            // $servei($data);

            return $response->withStatus(302)->withHeader('Location','/');

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
            $stmt = $servei($data);
            if (!empty($stmt[0]['id'])) {
                //echo("EXISTE");
                /**
                 * landingProfile -> Landing, quan el usuari s'hagi pogut loguejar
                 *  **/



                /**
                 * Si el usuario registrado le he enviado
                 * un correo de verifiacion de la cuenta
                 * y no ha verificado y intenta loguearse
                 * warning
                 */


                if($stmt[0]['verification'] == 0){
                    //Advertencia
                    //echo "<script>alert(\"Activa Cuenta.\");location.href='/log' </script> ";
                    $advertencia=0;
                    $ok=1;
                    $email = $stmt[0]['email'];

                    return $this->container
                        ->get('view')
                        ->render($response,'login.twig',['errors' => $errors,'ok' => $ok,'advertencia'=> $advertencia,'email'=>$email]);

                }else {
                    $_SESSION['id'] = $stmt[0]['id'];

                    //return $response->withStatus(302)->withHeader('Location', '/lp');
                }


            } else {
                /**
                 * No existe el usuario
                 */
                $ok = 0;
                return $this->container
                    ->get('view')
                    ->render($response, 'login.twig', ['errors' => $errors,'ok'=> $ok ]);

            }
        } else {
            return $this->container
                ->get('view')
                ->render($response, 'login.twig', ['errors' => $errors]);
        }

    }

    public function activacioEmailAgain(Request $request, Response $response){

         $data = $request->getParsedBody();
         $email = $data['emailuser'];

        /**
         * Servei per agafar el id del usuari
         * a partir del email
         */
        $servei = $this->container->get('get_id_with_email');
        $id = $servei($email);

        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername ('kkaarme11@gmail.com')
            ->setPassword ('carmexuxigemma');

        $mailer = (new Swift_Mailer($transport));

        $message = (new Swift_Message('HOLA'))
            ->setFrom('send@example.com')
            ->setTo($email)
            ->setBody(
                '<html>
            <head>
            <title>Activate your account</title>
            </head>
            <body>
            <h1>Validate your account {{ id }}</h1>
            <a href="http://slimapp.test/changeValidation/'.$id.'">Click here to validate your account!</a>
            </body>
            </html>'
            )
        ;
        $mailer->send($message);

        return $response->withStatus(302)->withHeader('Location','/');

    }


    public function profileUpdate(Request $request, Response $response, $data) {
        //$data = $request->getParsedBody();
        //la data del ajax
        var_dump($data);

        $servei = $this->container->get('update_user_use_case');
        $errors = $this->validacions($data, 0);
        if ($errors[0] == "" && $errors[1] == "" && $errors[2] == "" && $errors[3] == "" && $errors[4] == "" && $errors[5] == "") {

            //QUE DEVUELVA AL JS
           // return $response->withStatus(302)->withHeader('Location', '/prof');
            if($_FILES['myfile']['name']!=""){
                $id = $_SESSION['id'];
                $servei_check = $this->container->get('check_user_use_case');
                $info = $servei_check($id);
                if($info[0]['image']=="user.png"){
                    $route = "assets/img/".$info[0]['image'];
                    $destination ="assets/img/".$data["username"].".".$_FILES["myfile"]["name"];
                    $data["myfile"] = $data["username"].".".$_FILES["myfile"]["name"];
                    $name= $data["username"].".".$_FILES["myfile"]["tmp_name"];

                    $test = move_uploaded_file($_FILES['myfile']['tmp_name'],$destination);

                }else{
                    $route = "assets/img/".$info[0]['image'];
                    $test = unlink($route);
                    $destination ="assets/img/".$data["username"].".".$_FILES["myfile"]["name"];
                    $data["myfile"] = $data["username"].".".$_FILES["myfile"]["name"];
                    $name= $data["username"].".".$_FILES["myfile"]["tmp_name"];

                    $test = move_uploaded_file($_FILES['myfile']['tmp_name'],$destination);
                }



            }else{
                $data["myfile"] = "user.png";
            }
            $servei($data);
        }
    }

    public function inicioDashboard(Request $request, Response $response){
        try {
            return $response->withStatus(302)->withHeader('Location','/lp');

        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
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
            $servei_check = $this->container->get('check_user_use_case');
            $info = $servei_check($id);

            $route = "assets/img/".$info[0]['image'];
            var_dump($route);
            $servei= $this->container->get('delete_user_use_case');
            $stmt = $servei($id);
            if($stmt->execute()){
                echo "<script>alert(\"Record Deleted\");location.href='/'</script>";
                if($info[0]['image']!= "user.png"){
                    $test = unlink($route);
                }

                $_SESSION['id']= null;
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
            //var_dump($request->getParsedBody());
            $file = $request->getParsedBody();
            $file_name = $file['file_name'];
            $file_new_name = $file['titleFile'];
            $servei = $this->container->get('rename_file_user_use_case');


            /*
             * rename el fitxer de la carpeta item
             */
            $id=$_SESSION['id'];
            $array = explode(".",$file_name);
            $ext = $array[count($array)-1];
            $file_new_name = $file_new_name.".".$ext;

            $route_abans = "assets/item/".$id.".".$file_name;
            $route_ara = "assets/item/".$id.".".$file_new_name;

            rename($route_abans, $route_ara);
            $servei($file_name,$file_new_name);

            /**
             * Servei per buscar el folder de la file
             */

            $servei_folder = $this->container->get('folder_file_user_use_case');
            $folder_id = $servei_folder($file['file_id']);

            /**
             * Si este file esta dentro de una carpeta
             */
            if($folder_id != null){
                $servei_info = $this->container->get('check_share_user_use_case');
                $info = $servei_info($folder_id);
                $id_owner = $info[0]['id_owner'];
                if($id_owner == $_SESSION['id']){
                    $tipo = 2;
                }else{
                    $tipo=1;
                }
                $servei = $this->container->get('check_user_use_case');
                $user = $servei($_SESSION['id']);
                $notificacion = " El usuario ".$user[0]['username']." te ha renombrado el file ".$file_name."";
                $servei_save_notificacion = $this->container->get('save_notificacion');
                $servei_save_notificacion($id_owner,$_SESSION['id'],$folder_id,$notificacion,$tipo);


            }

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
            $id = $_SESSION['id'];

            /*
             * servei per tindre la info del file segons el seu if
             */
            $servei_file = $this->container->get('check_file_id_user_use_case');
            $info_file = $servei_file($file_id);
            $name_file = $info_file[0]['name'];


            /**
             * Servei per agafar el size del fitxer
             *per despres fer un altre servei que
             * sumi aquest size a la capacitat que
             * te el usuari per emmagatzemar info
             */

            $servei_get_file_size = $this->container->get('file_size_user_use_case');
            $filesize=$servei_get_file_size($file_id);

            $servei_capacity = $this->container->get('capacity_user_use_case');
            $capacity = $servei_capacity();

            $sum_capacity = $capacity + $filesize;
            $servei_actualitzar_capacitity = $this->container->get('actualitzar_capacity_user_use_case');
            $servei_actualitzar_capacitity($sum_capacity);


            /**
             * Servei delete file
             */
            $servei = $this->container->get('delete_file_user_use_case');
            $servei($file_id);
            /*
             * eliminem el fitxer de la carpeta item
             */
            $route = "assets/item/".$id.".".$name_file;

            $test = unlink($route);

            /**
             * Servei per buscar el folder de la file
             */

            $servei_folder = $this->container->get('folder_file_user_use_case');
            $folder_id = $servei_folder($file_id);

            /**
             * Si este file esta dentro de una carpeta
             */
            if($folder_id != null){
                $servei_info = $this->container->get('check_share_user_use_case');
                $info = $servei_info($folder_id);
                $id_owner = $info[0]['id_owner'];
                if($id_owner == $_SESSION['id']){
                    $tipo = 2;
                }else{
                    $tipo=1;
                }

                $servei = $this->container->get('check_user_use_case');
                $user = $servei($_SESSION['id']);
                $notificacion = " El usuario ".$user[0]['username']." te ha eliminado el file ".$file['file_name']."";
                $servei_save_notificacion = $this->container->get('save_notificacion');
                $servei_save_notificacion($id_owner,$_SESSION['id'],$folder_id,$notificacion,$tipo);

            }



            return  $response->withStatus(302)->withHeader('Location', '/lp');


        }

    }

    public function uploadFileProfile(Request $request, Response $response){

        $id = $_SESSION['id'];
        /*
         * Check user para la imagen
         */
        $servei_user = $this->container->get('check_user_use_case');
        $info_user = $servei_user($id);
        $image = $info_user[0]['image'];
        /**
         * Upload file
         */
        if(isset($_POST['uploadSubmit'])){
            $file = $_FILES['addFile'];
            /*
             * mirar el numero de files i hacer un bucle
             */
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

                    /**
                     * Servei para mirar la capacidad que tiene el usuario
                     */

                    $servei_capacity = $this->container->get('capacity_user_use_case');
                    $capacity = $servei_capacity();

                    if($capacity>$filesize) {
                        $restarcapacity= $capacity - $filesize;

                        //POTS AFEGIR FITXER
                        //HI HA QUE RESTAR LA CAPACITAT DEL USER
                        /**
                         * Servei per restar capacitat del arxiu a la capacitat del usuari
                         */

                        $servei_actualitzar_capacitity = $this->container->get('actualitzar_capacity_user_use_case');
                        $servei_actualitzar_capacitity($restarcapacity);



                        $id = $_SESSION['id'];

                        $item = $request->getParsedBody();
                        $id_folder = $item['uploadSubmit'];

                        if ($id_folder == '') {
                            $id_folder = 0;
                        }

                        $servei = $this->container->get('add_file_user_use_case');

                        /**
                         * Et retorna el número de fitxers
                         * i tota la informació dels fitxers
                         */
                        $info = $servei($file, $id, $id_folder,$filesize);
                        /*
                         * guardar el fitxer a la carpeta item (ens el guardem al el id de la session)
                         */
                        $name_img= $_FILES["addFile"]["name"];
                        $destination ="assets/item/".$id.".".$_FILES["addFile"]["name"];
                        $test = move_uploaded_file($_FILES['addFile']['tmp_name'],$destination);




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
                            ->render($response, 'dashboard.twig', ['item' => $array,'image' => $image]);

                        return $response->withStatus(302)->withHeader('Location', '/lp');

                    }else{
                        echo "<script>alert(\"Error, Not Enough Capactity to Upload File\");location.href='/lp'</script>";

                    }

                }
            }

        }

    }

    public function uploadFileInsideShare(Request $request, Response $response){
        $id = $_SESSION['id'];
        /*
         * Check user para la imagen
         */
        $servei_user = $this->container->get('check_user_use_case');
        $info_user = $servei_user($id);
        $image = $info_user[0]['image'];

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

                    /**
                     * Servei para mirar la capacidad que tiene el usuario
                     */

                    $servei_capacity = $this->container->get('capacity_user_use_case');
                    $capacity = $servei_capacity();

                    if($capacity>$filesize) {
                        $restarcapacity= $capacity - $filesize;

                        //POTS AFEGIR FITXER
                        //HI HA QUE RESTAR LA CAPACITAT DEL USER
                        /**
                         * Servei per restar capacitat del arxiu a la capacitat del usuari
                         */

                        $servei_actualitzar_capacitity = $this->container->get('actualitzar_capacity_user_use_case');
                        $servei_actualitzar_capacitity($restarcapacity);



                        $id = $_SESSION['id'];

                        $item = $request->getParsedBody();
                        $id_folder = $item['uploadSubmit'];


                        /*
                         * Si me printas un fichero fuera de una carpeta
                         * estara en la carpeta principal
                         */

                        if ($id_folder == '') {
                            $id_folder = 0;
                        }

                        /**
                         * En el share se guarda el id_parent ,de la folder
                         * AQUIII MAL ESTA ALGO!!
                         */





                        //$servei_share = $this->container->get('check_share_user_use_case');

                        /*NO ME SIRVE
                         * $servei_share = $this->container->get('check_share_user_use_case');
                        $info_share = $servei_share($id_folder);



                        $id_owner=$info_share[0]['id_owner'];
                        */
                        /**
                         * voy a buscar en checkFolders el id_user, para que sea mi id_owner
                         * siendo que el id_folder (de mi fichero) es el mismo que el id de la folder que busco
                         */
                        $servei_infofolder=  $this->container->get('check_this_folder_user_use_case');
                        $info_folder = $servei_infofolder($id_folder);
                        $id_owner = $info_folder[0]['id_user'];




                        $servei = $this->container->get('add_inside_share_file_user_use_case');

                        /**
                         * Et retorna el número de fitxers
                         * i tota la informació dels fitxers
                         */
                        $info = $servei($file, $id_owner, $id_folder,$filesize);


                        /*
                         * guardar el fitxer a la carpeta item (ens el guardem al el id de la session)
                         */
                        $name_img= $_FILES["addFile"]["name"];
                        $destination ="assets/item/".$id_owner.".".$_FILES["addFile"]["name"];
                        $test = move_uploaded_file($_FILES['addFile']['tmp_name'],$destination);

                        // Pq mho guardava com a string i ho vui amb int
                        $num_items = (int)$info[0];
                        // var_dump($num_items);
                        $img = "/assets/img/file.png";


                        $array = [];
                        for ($i = 0; $i < $num_items; $i++) {
                            $item = new Item($info[1][$i]['name'], $img, $id, $info[1][$i]['id'], $id_folder);
                            array_push($array, $item);
                        }

                        if($id_owner == $_SESSION['id']){
                            $tipo = 2;
                        }else{
                            $tipo=1;
                        }

                        $servei = $this->container->get('check_user_use_case');
                        $user = $servei($_SESSION['id']);
                        $notificacion = " El usuario ".$user[0]['username']." te ha upload el file ".$filename."";
                        $servei_save_notificacion = $this->container->get('save_notificacion');
                        $servei_save_notificacion($id_owner,$_SESSION['id'],$id_folder,$notificacion,$tipo);



                        $this->container
                            ->get('view')
                            ->render($response, 'dashboard.twig', ['item' => $array, 'image' => $image]);

                        return $response->withStatus(302)->withHeader('Location', '/lp');

                    }else{
                        echo "<script>alert(\"Error, Not Enough Capactity to Upload File\");location.href='/lp'</script>";

                    }

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
            /*
             * Check user para la imagen
             */
            $servei_user = $this->container->get('check_user_use_case');
            $info_user = $servei_user($id);
            $image = $info_user[0]['image'];
            //var_dump($image);

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
                ->render($response, 'dashboard.twig', ['folder' => $array, 'image' => $image]);

                return $response->withStatus(302)->withHeader('Location', '/lp');

        }
    }

    public function addFolderInsideShare(Request $request, Response $response){
        if(isset($_POST['addSubmit'])) {

            $id = $_SESSION['id'];
            /*
             * Check user para la imagen
             */
            $servei_user = $this->container->get('check_user_use_case');
            $info_user = $servei_user($id);
            $image = $info_user[0]['image'];
            //var_dump($image);


            $foldern = $request->getParsedBody();
            $folder_name = $foldern['nameFolder'];
            $id_parent = $foldern['addSubmit'];




            //hacemos un servicio para saber el id_user de la carpeta
            $servei_id_user = $this->container->get('check_user_folder_use_case');
            $info_user = $servei_id_user($foldern['folder_id']);
            $id_user = $info_user[0]['id_user'];

           // $id_folder = $foldern['folder_id'];

            $servei_share = $this->container->get('folders_shared_user_use_case');
            $info_share = $servei_share($id);
            var_dump($info_share);
            echo "------------";
            var_dump($info_share[0]['id_owner']);

            $id_owner = $info_share[0]['id_owner'];


            if($id_parent == ''){
                $id_parent=0;
            }

            /*$servei = $this->container->get('add_folder_user_use_case');
            $info = $servei((int)$id,$folder_name,$id_parent);
            */
            $servei = $this->container->get('add_inside_share_folder_user_use_case');
            $info = $servei($id_owner,$folder_name,$id_parent);
            var_dump($info);

            $num_folders = (int)$info[0];

            $img = "/assets/img/folder.png";


            $array = [];
            for ($i = 0; $i < $num_folders -1; $i++) {

                $folder = new Folder($info[1][$i]['name'], $img, $id, $info[1][$i]['id'], $id_parent);
                array_push($array, $folder);

            }



            $servei_info = $this->container->get('check_share_user_use_case');
            $infor = $servei_info($id_parent);
            $id_owner = $infor[0]['id_owner'];
            if($id_owner == $_SESSION['id']){
                $tipo = 2;
            }else{
                $tipo=1;
            }


            $servei = $this->container->get('check_user_use_case');
            $user = $servei($_SESSION['id']);
            $notificacion = " El usuario ".$user[0]['username']." te ha upload la carpeta ".$folder_name."";
            $servei_save_notificacion = $this->container->get('save_notificacion');
            $servei_save_notificacion($id_owner,$_SESSION['id'],$id_parent,$notificacion,$tipo);



            $this->container
                ->get('view')
                ->render($response, 'dashboard.twig', ['folder' => $array, 'image' => $image]);

            return $response->withStatus(302)->withHeader('Location', '/lp');

        }
    }

    public  function printFileFolder(Request $request, Response $response){

        $id = $_SESSION['id'];
        /*
         * Check user para la imagen
         */
        $servei_user = $this->container->get('check_user_use_case');
        $info_user = $servei_user($id);
        $image = $info_user[0]['image'];
        //var_dump($image);

        $fold = $request->getParsedBody();
        $id = $_SESSION['id'];
        //hacemos un servicio para saber el id_user de la carpeta
        $servei_id_user = $this->container->get('check_user_folder_use_case');
        $info_user = $servei_id_user($fold['folder_id']);
        $id_user = $info_user[0]['id_user'];
        $_SESSION['id_share']= $id_user;



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

        $_SESSION['id_share']= null;




        return $this->container
            ->get('view')
            ->render($response, 'dashboard.twig', ['folder' => $array2,'item' => $array ,"idParent" => $id_folder , "idFolder" => $id_folder, 'image' => $image]);



    }


    /**
     * Cuando printas files,folders de las carpetas que estan en la
     * carpeta compartida que te han enviado
     */
    public function printSharedFileFolder(Request $request, Response $response){
        $fold = $request->getParsedBody();

        $id = $_SESSION['id'];
        /*
         * Check user para la imagen
         */
        $servei_user = $this->container->get('check_user_use_case');
        $info_user = $servei_user($id);
        $image = $info_user[0]['image'];


        //hacemos un servicio para saber el id_user de la carpeta
        $servei_id_user = $this->container->get('check_user_folder_use_case');
        $info_user = $servei_id_user($fold['folder_id']);
        $id_user = $info_user[0]['id_user'];
        $_SESSION['id_share']= $id_user;



        $id_folder = $fold['folder_id'];

        $servei_share = $this->container->get('folders_shared_user_use_case');
        $info_share = $servei_share($id);
        var_dump($info_share);
        echo "------------";
        var_dump($info_share[0]['id_owner']);

        $id_owner = $info_share[0]['id_owner'];

       // $servei = $this->container->get('check_file_user_use_case');
        $servei = $this->container->get('check_share_file_user_use_case');

        /**
         *Tota la informació dels fitxers
         */
        //$info= $servei($id_folder,$id);
        $info= $servei($id_folder,$id_owner);


        $img = "/assets/img/file.png";

        $num_items = sizeof($info);

        $array = [];
        for($i=0;$i<$num_items;$i++){
            $item = new ItemShared($info[$i]['name'],$img,$id,$info[$i]['id'],$id_folder,$fold['role']);
            array_push($array,$item);
        }

        /*
         * folders
         */
        //$servei2 = $this->container->get('check_folder_user_use_case');
        $servei2 = $this->container->get('check_share_folder_user_use_case');

        /**
         *Tota la informació de la carpeta
         */
       // $info2 = $servei2($id_folder,$id);
        $info2 = $servei2($id_folder,$id_owner);

        $img2 = "/assets/img/folder.png";

        $num_folders = sizeof($info2);



        $array2 = [];
        for($i=0;$i<$num_folders;$i++){
            $folder = new FolderShared($info2[$i]['name'],$img2,$id,$info2[$i]['id'],$id_folder,$fold['role']);
            array_push($array2,$folder);
        }

        /**
         * Esto es pq si cuando entras en una carpeta admin i que no contiene nada dentro
         * te tiene que mostrar el boton de uploadfile,addfolder
         */
        $vacio_folder=0;
        $vacio_item = 0;

        if(empty($array)){
            $vacio_folder = 1;
        }
         if(empty($array2)){
            $vacio_item = 1;

         }


        $_SESSION['id_share']= null;




        return $this->container
            ->get('view')
            ->render($response, 'dashboardShareFolder.twig', ['folder' => $array2,'item' => $array ,"idParent" => $id_folder , "idFolder" => $id_folder, 'vacio_folder' => $vacio_folder,'vacio_item' => $vacio_item, 'role'=>$fold['role'], 'image' => $image]);

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

            // cojo el id_owner de la tabla share con el id_folder que quiero renombrar
            //if($info[0]['id_owner'] != $_SESSION['id']){}
            // significa que tengo que enviar notificacion del id_usershared al id_owner
                //abfjasbfsbfjkbs

            $servei_info = $this->container->get('check_share_user_use_case');
            $info = $servei_info($folder['folder_id']);
            $id_owner = $info[0]['id_owner'];
            if($id_owner == $_SESSION['id']){
                $tipo = 2;
            }else{
                $tipo=1;
            }

            $servei = $this->container->get('check_user_use_case');
            $user = $servei($_SESSION['id']);
            $notificacion = " El usuario ".$user[0]['username']." te ha renombrado la carpeta ".$folder['folder_name']."";
            $servei_save_notificacion = $this->container->get('save_notificacion');
            $servei_save_notificacion($id_owner,$_SESSION['id'],$folder['folder_id'],$notificacion,$tipo);



            return  $response->withStatus(302)->withHeader('Location', '/lp');


        }
    }

    public function deleteFolderProfile(Request $request, Response $response){
        /**
         * Delete file
         */
        if(isset($_POST['deleteFolder'])){
            $id=$_SESSION['id'];
            $folder = $request->getParsedBody();

            $folder_id = $folder['folder_id'];
            /*
             * mirem els fitxer que hi han, si es que hi han
             */
            $servei = $this->container->get('check_file_user_use_case');
            $files = $servei($folder_id,$id);
            /*
             * mirem els folder que hi ha dins, si es que hi han
             */
            $servei2 = $this->container->get('check_folder_user_use_case');
            $folders = $servei2($folder_id,$id);
            if(count($files)>0 || count($folders)>0){
                echo"YAAAAAS";
                $this->deleteAllFolder($files, $folders);
            }


            /**
             * Servei que em busca el id_parent de id_folder
             * pq sera el que tenim en la taula share ,
             * del qual podrem treure el seu id_owner
             */


            $servei_id_parent = $this->container->get('check_user_folder_use_case');
            $info_user = $servei_id_parent($folder_id);

            $id_parent = $info_user[0]['id_parent'];





            $servei_info = $this->container->get('check_share_user_use_case');
            $info = $servei_info($id_parent);

            $id_owner = $info[0]['id_owner'];



            $servei3 = $this->container->get('delete_folder_user_use_case');
            $servei3($folder_id);

            if($id_owner == $_SESSION['id']){
                $tipo = 2;
            }else{
                $tipo=1;
            }


            $servei5 = $this->container->get('check_user_use_case');
            $user = $servei5($_SESSION['id']);
            $notificacion = " El usuario ".$user[0]['username']." te ha eliminado la carpeta ".$folder['folder_name']."";
            $servei_save_notificacion = $this->container->get('save_notificacion');
            $servei_save_notificacion($id_owner,$_SESSION['id'],$folder_id,$notificacion,$tipo);



            /*
             * mira si hi esta compartit i ho elimina
             */
            $servei4= $this->container->get('delete_share_user_use_case');
            $servei4($folder_id);

            //var_dump($folder);

            //$servei = $this->container->get('delete_folder_user_use_case');
            //$servei($folder_id);




            return  $response->withStatus(302)->withHeader('Location', '/lp');


        }

    }
    /*
     * funcio recursiva per eliminar tot el contingut d'una folder
     */
    public function deleteAllFolder($info_files, $info_folders){
        $id=$_SESSION['id'];
        /*
         * for de files
         */
        for ($i=0; $i<count($info_files); $i++){
            /**
             * Servei per agafar el size del fitxer
             *per despres fer un altre servei que
             * sumi aquest size a la capacitat que
             * te el usuari per emmagatzemar info
             */

            $servei_get_file_size = $this->container->get('file_size_user_use_case');
            $filesize=$servei_get_file_size($info_files[$i]['id']);

            $servei_capacity = $this->container->get('capacity_user_use_case');
            $capacity = $servei_capacity();

            $sum_capacity = $capacity + $filesize;
            $servei_actualitzar_capacitity = $this->container->get('actualitzar_capacity_user_use_case');
            $servei_actualitzar_capacitity($sum_capacity);
                /*
                 * delete the files
                 */
                $servei = $this->container->get('delete_file_user_use_case');
                $servei($info_files[$i]['id']);
        }
        /*
         * for de folders
         */
        for ($i=0;$i<count($info_folders);$i++){
            /*
             * mirem els fitxer que hi han, si es que hi han
             */
            $folder_id=$info_folders[$i]['id'];
            $servei = $this->container->get('check_file_user_use_case');
            $files = $servei($folder_id,$id);
            /*
             * mirem els folder que hi ha dins, si es que hi han
             */
            $servei2 = $this->container->get('check_folder_user_use_case');
            $folders = $servei2($folder_id,$id);
            if(count($files)>0 || count($folders)>0){
                echo"YAAAAAS";
                $this->deleteAllFolder($files, $folders);
            }
            $servei3 = $this->container->get('delete_folder_user_use_case');
            $servei3($folder_id);
            /*
             * mira si esta compartit i ho elimina de share
             */
            $servei4= $this->container->get('delete_share_user_use_case');
            $servei4($folder_id);

        }

    }



    /**
     * ROLE READER
     */

    public function downloadFileProfile(Request $request, Response $response){
         if(isset($_POST['downloadFile'])){
             $file = $request->getParsedBody();
             $file_name = $file['file_name'];
             $id=$_SESSION['id'];
             $route = __DIR__ . "/../../public/assets/item/".$id.".".$file_name;
             $name = $id.".".$file_name;
             if(file_exists($route)){

                 header('Content-Description: File Transfer');
                 header('Content-Type: application/octet-stream');
                 header('Content-Disposition: attachment; filename="'.basename($name).'"');
                 header('Expires: 0');
                 header('Cache-Control: must-revalidate');
                 header('Pragma: public');
                 header('Content-Length: ' . filesize($route));
                 readfile($route);
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
            $servei = $this->container->get('check_email_share_user_use_case');
            $info = $servei($mail);

            /**
             * Si el email no existe -> el id sera 0 el que me devuelve
             * Saltara error
             */
            if(empty($info)){

                echo "<script>alert(\"ERROR: El usuario que estas intendo enviar la carpeta no esta registrado \");location.href='/lp'</script>";

            }else{
                $id_usershared= $info[0]['id'];

                $tipo = $_POST['roles'];
                if($tipo == null){
                    $tipo="Reader";
                }

                $servei = $this->container->get('add_share_user_use_case');
                $servei($id_owner,$id_usershared,$id_folder,$tipo);


                /**
                 * Tengo que enviarle una notificación al id_usershared
                 * de que si la carpeta que le paso , la hago admin,
                 * este id_usershared podra ser administrador de esta
                 */

                if($tipo == 'Admin'){
                    $servei_infofolder = $this->container->get('check_this_folder_user_use_case');
                    $info_folder = $servei_infofolder($folder['folder_id']);
                    $id_ownerP = $info_folder[0]['id_user'];
                    /*
                    $servei_info = $this->container->get('check_share_user_use_case');
                    $info = $servei_info($folder['folder_id']);
                    $id_ownerP = $info[0]['id_owner'];
                    */
                    if($id_ownerP == $_SESSION['id']){
                        $tipo = 2;
                    }else{
                        $tipo=1;
                    }
                    $servei = $this->container->get('check_user_use_case');
                    $user = $servei($id_ownerP);


                    $notificacion = " El usuario ".$user[0]['username']." te ha hecho Administrador de ".$folder['folder_name']."";
                    $servei_save_notificacion = $this->container->get('save_notificacion');
                    $servei_save_notificacion($id_ownerP,$id_usershared,$id_folder,$notificacion,$tipo);
                }


                return $response->withStatus(302)->withHeader('Location', '/lp');
            }
        }
    }

    /**
     *Cuando entras en la carpeta share
     */

    public function sharedFolders(Request $request, Response $response){
        if(isset($_POST{'sharedFolder'})){

            $id = $_SESSION['id'];
            /*
             * Check user para la imagen
             */
            $servei_user = $this->container->get('check_user_use_case');
            $info_user = $servei_user($id);
            $image = $info_user[0]['image'];

            $_SESSION['id_share'] = null;
            $id_usershared = $_SESSION['id'];
            $servei = $this->container->get('folders_shared_user_use_case');
            $folders = $servei($id_usershared);

            $num_folders = sizeof($folders);

            $array = [];
            $img2 = "/assets/img/folder.png";
            $id_parent = 1; // si esta en share, necesito la id_parent?

            for($i=0; $i<$num_folders;$i++){
                $shared = $folders[$i]['id_folder'];
                /**
                 * Depende del tipo mostrarà folders de admin o reader
                 */
                $type = $folders[$i]['type'];


                $servei4 = $this->container->get('check_folders_shared_user_use_case');

                $folder_shared = $servei4($shared);
                $folder = new FolderShared($folder_shared[0]['name'],$img2,$id_usershared,$folder_shared[0]['id'],1,$type);

               // $folder = new Folder($folder_shared[0]['name'],$img2,$id_usershared,$folder_shared[0]['id'],1);
                array_push($array,$folder);

            }

            return $this->container
                ->get('view')
                ->render($response, 'dashboardexterno.twig', ['folder' => $array, 'image' => $image]);


        }
    }


    /**
     *Printar Notificacions
     */

    public function notificationsUser(Request $request, Response $response){
        /**
         * Servei que me busca las notificaciones:
         * id_owner tiene que ser igual al session_id
         */


        $servei = $this->container->get('get_notifications_user');
        $notifications = $servei();


        $num_notifications = sizeof($notifications);

        $array_owner_notifications=[];
        $array_user_shared_notifications=[];


        for($i=0;$i<$num_notifications;$i++){
            /**
             * Para el usuario que esta compartiendo
             */
            if($notifications[$i]['tipo'] == 2 && $notifications[$i]['id_owner'] != $_SESSION['id']){
                array_push($array_owner_notifications,$notifications[$i]);
            }
            //$notifications[$i]['id_owner']
            /**
             * Para el usuario compartido
             */

            if($notifications[$i]['tipo'] == 1 && $notifications[$i]['id_usershared'] == $_SESSION['id']){
                 array_push($array_user_shared_notifications,$notifications[$i]);
            }
        }







        return $this->container
            ->get('view')
            ->render($response, 'notifications.twig', ['notificationsOwner' => $array_owner_notifications,'notificationsUserShared' => $array_user_shared_notifications,'id'=>$_SESSION['id']]);


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
                    //echo "NO ERROR IMG";
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

    public function changeValidation (Request $request, Response $response,$id){
       //ACCEDIR A LA BBDD I CANVIAR COLUMNA DE VALIDACIO DEL ID QUE S'HA GUARDAT
        $_SESSION['id'] = $id['id'];
        $servei = $this->container->get('post_verification');
        $servei($id);
        return $response->withStatus(302)->withHeader('Location', '/lp');


        //QUAN S'HA FET EL CANVI DE VALIDACIO VAS AL LOGIN
    }
}




  /*public function __invoke(Request $request, Response $response, array $args){
    $name = $args['name'];
    return $this->container
          ->get('view')
          ->render($response, 'hello.twig', ['name' => $name]);
  }*/




 ?>
