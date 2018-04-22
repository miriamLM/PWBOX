<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 18/5/18
 * Time: 20:25
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class EmailUniqueUserUseCase
{
    private $repository;


    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke($email) {


        $email = $this->repository->emailunique($email);

        return $email;
    }
}