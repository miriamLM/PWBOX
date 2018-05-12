<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 12/05/2018
 * Time: 13:53
 */

namespace SlimApp\model;


class Folder
{
    private $id;
    private $id_user;
    private $img;
    private $nom;
    private $id_folder;



    public function __construct($nom,$img,$id_user,$id,$id_folder)
    {
        $this->nom = $nom;
        $this->img = $img;
        $this->id_user= $id_user;
        $this->id= $id;
        $this->id_folder= $id_folder;

    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param mixed $id_user
     */
    public function setIdUser($id_user): void
    {
        $this->id_user = $id_user;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param mixed $img
     */
    public function setImg($img): void
    {
        $this->img = $img;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getIdFolder()
    {
        return $this->id_folder;
    }

    /**
     * @param mixed $id_folder
     */
    public function setIdFolder($id_folder): void
    {
        $this->id_folder = $id_folder;
    }



}