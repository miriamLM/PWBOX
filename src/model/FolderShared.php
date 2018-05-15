<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 15/05/2018
 * Time: 13:14
 */

namespace SlimApp\model;


class FolderShared
{

    private $id;
    private $id_user;
    private $img;
    private $nom;
    private $id_parent;

    /**
     * FolderShared constructor.
     * @param $id
     * @param $id_user
     * @param $img
     * @param $nom
     * @param $id_parent
     */
    public function __construct($id, $id_user, $img, $nom, $id_parent)
    {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->img = $img;
        $this->nom = $nom;
        $this->id_parent = $id_parent;
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
    public function getIdParent()
    {
        return $this->id_parent;
    }

    /**
     * @param mixed $id_parent
     */
    public function setIdParent($id_parent): void
    {
        $this->id_parent = $id_parent;
    }



}