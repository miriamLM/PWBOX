<?php

namespace SlimApp\model;


class User
{
    private $id;
    private $username;
    private $email;
    private $birthdate;
    private $password;
    private $createdAt;
    private $updatedAt;

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $email
     * @param $birthdate
     * @param $password
     * @param $createdAt
     * @param $updatedAt
     */
    public function __construct($id, $username, $email,$birthdate ,$password,$createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->birthdate = $birthdate;
        $this->password = $password;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return String
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }


    /**
     * @return String
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


}