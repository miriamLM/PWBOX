<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 12/4/18
 * Time: 20:23
 */

namespace SlimApp\model\Interfaces;


use SlimApp\model\User;

interface bbddRepository
{

    /**
     * Funcio que ens guarde un nou usuari a la bbdd
     **/

    public function save(User $user,$capacity);

    /**
     * Funcio que comprova si existeis el usuari, alhora de fer login
     **/

    public function exists($emailuser,$psw);

    /**
     *Coger informacion del usuario, cuando este este logueado, para poder llegar
     * a utilizarlo en el update
     */
    public function check($id);

    /**
     *   Actualiza información usuario
     */

    public function update($email,$psw);


    /**
     *  Elimina Usuari
     */

    public function delete($id);


    /**
     * Add new file
     */

    public function addfile($file,$id,$id_folder,$filesize);

    /**
     * Check files
     */

    public function checkfiles($folder_id,$id);

    /**
     * Delete files
     */

    public function deletefile($file_id);

    /**
     * Rename files
     */

    public function renamefile($name,$new_name);

    /**
     * Add folder
     */
    public function newFolder($id,$folder_name,$id_parent);

    /**
     * Rename folder
     */
    public function renamefolder($name,$new_name);

    /**
     * Check folders
     */
    public function checkfolders($folder_id,$id);

    /**
     * Delete folder
     */
    public function deletefolder($folder_id);

    /**
     * Check email shared
     */

    public function checkemailshare($email);

    /**
     * Add shared user
     */

    public function addshareuser($id_owner,$id_usershared,$id_folder,$type);

    /**
     * Get folders of id_usershared
     */

    public function foldershareduser($id_usershared);

    /**
     * Get folders of id_shared
     */

    public function checkFoldersShared($id_shared);

    /**
     * get id_user of a folder
     */

    public function checkUserFolder($folder_id);

    /**
     * get capacity user
     */

    public function capacityuser();

    /**
     * restar capacity user
     */

    public function restarcapacityuser($file_size);

    /**
     * get file size of the delete file
     */

    public function filesize($file_id);

}