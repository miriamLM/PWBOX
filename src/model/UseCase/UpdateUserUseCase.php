<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 3/5/18
 * Time: 19:07
 */

namespace SlimApp\model\UseCase;

use SlimApp\model\Interfaces\bbddRepository;
use SlimApp\model\User;

class UpdateUserUseCase
{
    /** @var bbddRepository */
    private $repository;


    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke(array $rawData) {


        $psswH = md5($rawData['psw']);

        $this->repository->update($rawData['email'],$psswH);


    }



}