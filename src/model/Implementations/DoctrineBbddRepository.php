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
    public function save(User $user,$capacity)
    {
        $sql = "INSERT INTO user(username, email,birthdate, password, created_at, updated_at,image,capacity,verification) VALUES(:username, :email,:birthdate ,:password, :created_at, :updated_at,:image,:capacity,:verification)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("username", $user->getUsername(), 'string');
        $stmt->bindValue("email", $user->getEmail(), 'string');
        $stmt->bindValue("birthdate",$user->getBirthdate());
        $stmt->bindValue("password", $user->getPassword(), 'string');
        $stmt->bindValue("created_at", $user->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("updated_at", $user->getCreatedAt()->format(self::DATE_FORMAT));
        $stmt->bindValue("image", $user->getImage(), 'string');
        $stmt->bindValue("capacity", $capacity);
        $stmt->bindValue("verification",$user->getVerification());

        //$stmt->bindValue("image", $user->getImage(), 'string');
        $stmt->execute();
    }

    public function verification($id)
    {
        $sql = "UPDATE user  AS u SET u.verification = :verification WHERE u.id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("verification", 1);
        $stmt->bindValue("id", $id);
        $stmt->execute();

    }

    public function existsUserValidation($email,$psw)
    {
        $sql = "SELECT * FROM user WHERE email= ?  AND password = ?";
        $stmt = $this->connection->fetchAll($sql, array($email,$psw));

        return $stmt[0]['id'];

    }

    public function exists($emailuser,$psw)
    {
        $sql = "SELECT * FROM user WHERE (email= ? OR username= ?)  AND password = ?";
        $stmt = $this->connection->fetchAll($sql, array($emailuser,$emailuser,$psw));
        /**
         * M'agafa tot l'array , el qual conte tota la informacio d'aquell usuari amb aquell
        email i password
         *
         **/
        //return $stmt[0]['id'];
        return $stmt;
    }


    public function getidwithemail($email){
        $sql = "SELECT * FROM user WHERE email= ?";
        $stmt = $this->connection->fetchAll($sql, array($email));
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

    public function update($email,$psw,$img){
        $sql = "UPDATE user  AS u SET u.email = :email, u.password = :password, u.image= :image WHERE u.id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("email", $email, 'string');
        $stmt->bindValue("password", $psw, 'string');
        $stmt->bindValue("image", $img, 'string');
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
    public function addfile($file, $id,$id_folder,$filesize)
    {
        $sql = "INSERT INTO item(id_user,name,id_folder,size) VALUES(:id_user, :name,:id_folder,:size)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_user", $id);
        $stmt->bindValue("name", $file['name'],'string');
        $stmt->bindValue("id_folder",$id_folder);
        $stmt->bindValue("size",$filesize);
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

    public function checkfiles($folder_id,$id)
    {
       /* $query = "SELECT * FROM item WHERE id_folder =? and (id_user=? OR id_user=?)";

        $info = $this->connection->fetchAll($query,array($folder_id,(int)$_SESSION['id'],(int)$_SESSION['id_share']));

        var_dump($id);

        return $info;
       */
        $query = "SELECT * FROM item WHERE id_folder =? and id_user=?";

        $info = $this->connection->fetchAll($query,array($folder_id,(int)$_SESSION['id']));

        var_dump($id);

        return $info;
    }

    public function checksharefiles($folder_id,$id_owner){
        $query = "SELECT * FROM item WHERE id_folder =? and id_user=?";

        $info = $this->connection->fetchAll($query,array($folder_id,$id_owner));

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

    public function filesize($file_id){
        $query = "SELECT size FROM item WHERE id =?";

        $info = $this->connection->fetchAll($query,array($file_id));

        return $info[0]['size'];
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

    public function checkfolders($folder_id,$id)
    {
        /*$query = "SELECT * FROM folder WHERE id_parent = ? and (id_user=? OR id_user=?)";
        $info = $this->connection->fetchAll($query,array($folder_id,(int)$_SESSION['id'],(int)$_SESSION['id_share']));
        return $info;
        */
        $query = "SELECT * FROM folder WHERE id_parent = ? and id_user=?";
        $info = $this->connection->fetchAll($query,array($folder_id,(int)$_SESSION['id']));
        return $info;

    }

    public function checksharefolders ($folder_id,$id_owner){
        $query = "SELECT * FROM folder WHERE id_parent = ? and id_user=?";
        $info = $this->connection->fetchAll($query,array($folder_id,$id_owner));
        return $info;
    }

    public function deletefolder($folder_id){
        $sql = "DELETE FROM folder WHERE id = ? ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $folder_id);
        $stmt->execute();

    }


    /**
     * SHARE, CHECK EMAIL
     * @param $email
     */
    public function checkemailshare($email){

        $query = "SELECT * FROM user WHERE email = ? ";
        $info = $this->connection->fetchAll($query,array($email));
        return $info;
    }

    /**
     * Add Shared user
     * @param $id_owner
     * @param $id_usershared
     * @param $id_folder
     * @param $type
     */

    public function addshareuser($id_owner,$id_usershared,$id_folder,$type){
        $sql = "INSERT INTO share(id_owner, id_usershared,id_folder, type) VALUES(:idowner, :idusershared,:idfolder ,:type)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("idowner",$id_owner);
        $stmt->bindValue("idusershared", $id_usershared);
        $stmt->bindValue("idfolder",$id_folder);
        $stmt->bindValue("type", $type, 'string');
        $stmt->execute();
    }

    /**
     * Folders shared id_usershared
     * @param $id_usershared
     * @return array
     */
    public function foldershareduser($id_usershared){
        $query = "SELECT * FROM share WHERE id_usershared = ? ";
        $info = $this->connection->fetchAll($query,array($id_usershared));
        return $info;
    }

    /**
     * @param $id_shared
     * @return array
     */

    public function checkFoldersShared($id_shared){
        $query = "SELECT * FROM folder WHERE id = ? ";
        $info = $this->connection->fetchAll($query,array($id_shared));
        return $info;

    }


    public function checkUserFolder($folder_id){
        $query = "SELECT * FROM folder WHERE id = ? ";
        $info = $this->connection->fetchAll($query,array($folder_id));
        return $info;
    }

    public function capacityuser(){
        $query = "SELECT capacity FROM user WHERE id = ?";
        $capacity= $this->connection->fetchAll($query,array($_SESSION['id']));
        return $capacity[0]['capacity'];
    }

    public function restarcapacityuser($file_size){
        $sql = "UPDATE user AS u SET u.capacity = :capacity WHERE u.id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id", $_SESSION['id']);
        $stmt->bindValue("capacity", $file_size);
        $stmt->execute();
    }

    /**
     * @param $id_folder
     */
    public function deleteshare($id_folder){
        $sql = "DELETE FROM share WHERE id_folder = ? ";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(1, $id_folder);
        $stmt->execute();
    }

/**
 * Para que el que ha compartido la carpeta pueda ver el contenido que añade la otra persona
 */
    public function newFolderInsideShare($id_owner,$folder_name,$id_parent){
        var_dump($id_parent);
        $sql = "INSERT INTO folder (id_user,name, id_parent) VALUES (:id_user,:nameFolder, :id_parent)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_user", $id_owner);
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

    public function addfileInsideShare($file,$id,$id_folder,$filesize){
        $sql = "INSERT INTO item(id_user,name,id_folder,size) VALUES(:id_user, :name,:id_folder,:size)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("id_user", $id);
        $stmt->bindValue("name", $file['name'],'string');
        $stmt->bindValue("id_folder",$id_folder);
        $stmt->bindValue("size",$filesize);
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


    public function checkshare($id_folder){
        $query = "SELECT * FROM share WHERE id_folder = ? and id_usershared = ?";
        $info= $this->connection->fetchAll($query,array($id_folder,$_SESSION['id']));
        return $info;
    }

    public function savenotificacion($id_owner,$id_usershared,$id_folder,$notificacion,$tipo){
        $sql = "INSERT INTO notificacion(id_owner, id_usershared,id_folder, notificacion, tipo) VALUES(:idowner, :idusershared,:idfolder ,:notificacion, :tipo)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue("idowner",$id_owner);
        $stmt->bindValue("idusershared", $id_usershared);
        $stmt->bindValue("idfolder",$id_folder);
        $stmt->bindValue("notificacion", $notificacion, 'string');
        $stmt->bindValue("tipo",$tipo);
        $stmt->execute();
    }

    public function folderfile($file_id){
        $query = "SELECT * FROM item WHERE id = ?";
        $info= $this->connection->fetchAll($query,array($file_id));
        return $info[0]['id_folder'];
    }


    public function getnotificationsuser(){
        $query = "SELECT * FROM notificacion";
        $info= $this->connection->fetchAll($query);
        return $info;
    }





    public function checkfilesId($file_id){
        $query = "SELECT * FROM item WHERE id =?";

        $info = $this->connection->fetchAll($query,array($file_id));

        return $info;
    }



    public function checkThisFolder($folder_id){
        $query = "SELECT * FROM folder WHERE id = ?";
        $info = $this->connection->fetchAll($query,array($folder_id));
        return $info;
    }







}