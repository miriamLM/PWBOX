<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 17/5/18
 * Time: 11:48
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class SaveNotificacionUserUsecase
{
    private $repository;


    public function __construct(bbddRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke($id_owner,$id_usershared,$id_folder,$notificacion) {


        $this->repository->savenotificacion($id_owner,$id_usershared,$id_folder,$notificacion);

    }


}