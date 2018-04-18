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
        $now = new \DateTime('now');
        $psswH = md5($rawData['psw']);

       /* $user = new User(
            null,
            null,
            $rawData['email'],
            null,
            $psswH,
            $now,
            $now
        );*/

        //$result = $this->repository->exists($user);
        $result = $this->repository->exists($rawData["email"],$psswH);

        return $result;
    }

}