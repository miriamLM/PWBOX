<?php

namespace SlimApp\model\Implementations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use SlimApp\model\User;
use SlimApp\model\Interfaces\bbddRepository;
class DoctrineBbddRepository implements bbddRepository
{
   private const DATE_FORMAT = 'Y/m/d  H:i:s';




    /** @var Connection  */
    private $connection;


    public function __construct(Connection $connection){
        $this->connection = $connection;
    }

    public function save(User $user)
    {
        $sql = "INSERT INTO user(username, email,birthdate, password, created_at, updated_at) VALUES(:username, :email,:birthdate ,:password, :created_at, :updated_at)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("birthdate",$user->getBirthdate());
        $stmt->bindValue("password", $user->getPassword(), 'string');
        $stmt->bindValue("created_at", $user->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $user->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->execute();
    }

    //$email, $psw
    public function exists($email,$psw)
    {
        $sql = "SELECT id FROM user WHERE email= ? AND password = ?";
        //$stmt = $this->connection->fetchAll($sql, array($user->getEmail(), $user->getPassword()));
        $stmt = $this->connection->fetchAll($sql, array($email, $psw));
        /**
         * M'agafa tot l'array , el qual conte tota la informacio d'aquell usuari amb aquell
            email i password
         *
         **/
        return $stmt[0]['id'];

    }

    public function check($id)
    {
        $sql = "SELECT * FROM user WHERE id= ?";
        $stmt = $this->connection->fetchAll($sql,array($id));
        /**
         * M'agafa tot l'array , el qual conte tota la informacio d'aquell usuari amb aquell
        email i password
         *
         **/
        var_dump($id);
        return $stmt;

    }


    public function update($email,$psw){
        $sql = "UPDATE user  AS u SET u.email = :email, u.password = :password WHERE u.id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("email", $email, 'string');
        $stmt->bindValue("password", $psw, 'string');
        $stmt->bindValue("id", $_SESSION['id'], 'integer');
        $stmt->execute();
    }
}