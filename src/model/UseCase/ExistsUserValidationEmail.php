<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 17/5/18
 * Time: 17:23
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class ExistsUserValidationEmail
{
    private $repository;


    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke(array $rawData) {
        $psswH = md5($rawData['psw']);


        $result = $this->repository->existsUserValidation($rawData["email"],$psswH);

        return $result;
    }

}