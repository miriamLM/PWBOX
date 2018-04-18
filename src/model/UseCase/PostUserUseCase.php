<?php

namespace SlimApp\model\UseCase;

use phpDocumentor\Reflection\Types\Array_;
use SlimApp\model\Interfaces\bbddRepository;
use SlimApp\model\User;

class PostUserUseCase
{
    /** @var bbddRepository */
    private $repository;


    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke(array $rawData) {
        $now = new \DateTime('now');
        $psswH = md5($rawData['psw']);



        $user = new User(
            null,
            $rawData['username'],
            $rawData['email'],
            $rawData['birthdate'],
            $psswH,
            $now,
            $now
        );


        $this->repository->save($user);


    }




}