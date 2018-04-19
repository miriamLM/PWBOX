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

    public function save(User $user);

    /**
     * Funcio que comprova si existeis el usuari, alhora de fer login
     **/

    public function exists($email,$psw);

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

    public function addfile($file,$id,$id_folder);

    /**
     * Check files
     */

    public function checkfiles();

    /**
     * Delete files
     */

    public function deletefile($file_id);

    /**
     * Rename files
     */

    public function renamefile($name,$new_name);


}