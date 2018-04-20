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


    /**
     * USER
     * @param User $user
     * @throws \Doctrine\DBAL\DBALException
     */
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


    /**
     * FILE
     * @param $file
     * @param $id
     * @param $id_folder
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
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

    public function checkfiles($folder_id)
    {
        $query = "SELECT * FROM item WHERE id_folder =? ";

        $info = $this->connection->fetchAll($query,array($folder_id));
        return $info;
    }

    public function deletefile($file_id){
        $sql = "DELETE FROM item WHERE id = ? ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $file_id);
        $stmt->execute();
    }

    public function renamefile($name,$new_name)
    {
        $sql = "UPDATE item AS i SET i.name = :newname WHERE i.name = :name";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("newname", $new_name, 'string');
        $stmt->bindValue("name", $name, 'string');
        $stmt->execute();
        /*if($stmt->execute()){
            $info = $this->checkfiles($folder_id);

        }
        return $info;*/

    }


    /**
     * FOLDER
     * @param $id
     * @param $folder_name
     * @param $id_parent
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */

    public function newFolder($id, $folder_name, $id_parent)
    {

        var_dump($id_parent);
        $sql = "INSERT INTO folder (id_user,name, id_parent) VALUES (:id_user,:nameFolder, :id_parent)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_user", $id);
        $stmt->bindValue("nameFolder", $folder_name);
        $stmt->bindValue("id_parent", $id_parent);
        if($stmt->execute()){
            $sql = "SELECT COUNT(*) FROM folder";
            $stmt = $this->connection->query($sql);
            $num_folders= $stmt->fetchColumn();
            var_dump($num_folders);

            $query = "SELECT * FROM folder WHERE id_parent =?";
            $info = $this->connection->fetchAll($query,array($id_parent));
            return [$num_folders,$info];

        }
    }

    public function renamefolder($name, $new_name)
    {
        $sql = "UPDATE folder AS f SET f.name = :newname WHERE f.name = :name";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("newname", $new_name, 'string');
        $stmt->bindValue("name", $name, 'string');
        $stmt->execute();
    }

    public function checkfolders($folder_id)
    {
        $query = "SELECT * FROM folder WHERE id_parent = ? ";
        $info = $this->connection->fetchAll($query,array($folder_id));
        return $info;
    }

    public function deletefolder($folder_id){
        $sql = "DELETE FROM folder WHERE id = ? ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $folder_id);
        $stmt->execute();

    }
}