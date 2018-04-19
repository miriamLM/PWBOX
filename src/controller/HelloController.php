<?php
namespace SlimApp\controller;

use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use PHPUnit\Runner\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Dflydev\FigCookies\FigRequestCookies;

class HelloController
{

    protected $container;
    const DATA = 'Y/m/d';


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
        if (!isset($_SESSION['counter'])) {
            $_SESSION['counter'] = 1;

        } else {
            $_SESSION['counter']++;

        }

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

    public function landingProfile(Request $request, Response $response)
    {
        try {
            $this->container->get('view')->render($response, 'dashboard.twig');
            if(isset($_POST['delete'])){
                echo "ENTRA";
                $id = $_SESSION['id'];
                $servei= $this->container->get('delete_user_use_case');
                $stmt = $servei($id);
                if($stmt->execute()){
                    echo "<script>alert('Record deleted.')</script>";

                    return $this->container->get('view')->render($response, 'landing.twig');
                }
            }
            if(isset($_POST['upload'])){
                echo "entra addfile";
                $file = $_FILES['addFile'];
                var_dump($_FILES);
            }
            if(isset($_POST['folder'])){
                mkdir(__DIR__.$_POST['folder']);
                $this->folder($request,$response);
                //return $this->container->get('view')->render($response,'dashboard.twig',['ok' => $ok]);

            }

        } catch (NotFoundExceptionInterface $e) {
        } catch (ContainerExceptionInterface $e) {
        }
    }


    public function folder(Request $request,Response $response){
        try {
            return $this->container->get('view')->render($response, 'dashboard.twig', ['ok' => true]);
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
        }

        return $this->container
            ->get('view')
            ->render($response, 'registration.twig', ['errors' => $errors]);

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

               // $this->profileAction($request,$response,$id);

                $_SESSION['id'] = $id;


                /*return $this->container
                    ->get('view')
                    ->render($response, 'dashboard.twig');*/

                return $this->landingProfile($request,$response);

            } else {
                echo "<script>alert('NO EXISTE USUARIO.')</script>";
            }
        } else {
            return $this->container
                ->get('view')
                ->render($response, 'login.twig', ['errors' => $errors]);
        }

        /*
         * Per fer l'update del profile, el usuari ha d'estar loguejat
         *
         */


        /* if (isset($_SESSION[$user["id"]])) {
             echo "LOGGED";
             //$this->profileAction($request,$response,$data);
         }else{
             echo "NOT LOGGED";
             //not logged
         }*/

    }

    public function profileUpdate(Request $request, Response $response) {

        echo "hola";

        $data = $request->getParsedBody();
        $servei = $this->container->get('update_user_use_case');
        $errors = $this->validacions($data, 0);
        echo "..";
        echo "hola2";
        if ($errors[0] == "" && $errors[1] == "" && $errors[2] == "" && $errors[3] == "" && $errors[4] == "") {            $servei($data);
            $servei($data);
            $user = [$data['username'],$data['email'],$data['birthdate'],$data['psw'],$data['psw']];
            var_dump($user);
            return $this->container
                ->get('view')
                ->render($response, 'profileUpdate.twig', ['user'=> $user],['errors' => $errors]);
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

                /*EXISTES?*/
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

                if (empty($rawData["email"])) {
                    $emailErr = "Email cannot be empty";
                } else {
                    if (!filter_var($rawData["email"], FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid email format";
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
