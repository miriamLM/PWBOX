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

class PostLoginUseCase
{
    private $repository;


    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke(array $rawData) {
        $psswH = md5($rawData['psw']);



        $result = $this->repository->exists($rawData["emailuser"],$psswH);

        return $result;
    }

}