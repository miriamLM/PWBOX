<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 14/5/18
 * Time: 18:29
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class CheckEmailShareUserUseCase
{
    private $repository;

    public function __construct(bbddRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke($mail) {

        /**
         * Tenemos que saber el id del usuario con este email
         * para despues guardar-lo en la tabla shared (id_usershared)
         */

        $id_usershared = $this->repository->checkemailshare($mail);

        return $id_usershared;

    }
}