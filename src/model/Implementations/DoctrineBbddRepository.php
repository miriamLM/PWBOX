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

    public function exists($emailuser,$psw)
    {
        $sql = "SELECT id FROM user WHERE (email= ? OR username= ?)  AND password = ?";
        $stmt = $this->connection->fetchAll($sql, array($emailuser,$emailuser,$psw));
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


    public function delete($id)
    {
        $sql = "DELETE FROM user WHERE id = ? ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id);
        return $stmt;
    }



    public function addfile($file, $id,$id_folder)
    {
        $sql = "INSERT INTO item(id_user,name,id_folder) VALUES(:id_user, :name,:id_folder)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_user", $id);
        $stmt->bindValue("name", $file['name'],'string');
        $stmt->bindValue("id_folder",$id_folder);
        if($stmt->execute()){
            $sql = "SELECT COUNT(*) FROM item";
            $stmt = $this->connection->query($sql);
            $num_items= $stmt->fetchColumn();
            var_dump($num_items);

            $query = "SELECT * FROM item";
            $info = $this->connection->fetchAll($query);
            var_dump($info);

            return [$num_items,$info];

        }
    }

    public function checkfiles()
    {
        $query = "SELECT * FROM item";
        $info = $this->connection->fetchAll($query);
        return $info;
    }


    public function deletefile($file_id){
        $sql = "DELETE FROM item WHERE id = ? ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $file_id);
        if($stmt->execute()){
            $info = $this->checkfiles();
        }
        return $info;
    }

    public function renamefile($name,$new_name)
    {
        $sql = "UPDATE item AS i SET i.name = :newname WHERE i.name = :name";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("newname", $new_name, 'string');
        $stmt->bindValue("name", $name, 'string');
        if($stmt->execute()){
            $info = $this->checkfiles();

        }
        return $info;

    }


}