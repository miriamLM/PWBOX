<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 18/5/18
 * Time: 15:58
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class GetIdWithEmail
{
    private $repository;

    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke($email) {
        /**
         * Para el reenvio de otro correo de autenticacion
         */

        $id = $this->repository->getidwithemail($email);

        return $id;

    }

}