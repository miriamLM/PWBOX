<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 17/4/18
 * Time: 11:52
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;
use SlimApp\model\Interfaces\UserRepository;
use SlimApp\model\User;

class PostVerification
{
    private $repository;


    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke($id) {


        $this->repository->verification($id['id']);


    }

}