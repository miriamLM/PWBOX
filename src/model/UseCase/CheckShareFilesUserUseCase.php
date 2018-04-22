<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 17/05/2018
 * Time: 10:14
 */

namespace SlimApp\model\UseCase;


class CheckShareFilesUserUseCase
{
    private $repository;

    /**
     * CheckShareFilesUserUseCase constructor.
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($folder_id,$id_owner) {



        $info = $this->repository->checksharefiles($folder_id,$id_owner);

        return $info;

    }
}