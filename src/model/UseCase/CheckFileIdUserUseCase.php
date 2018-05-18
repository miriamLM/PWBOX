<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 17/05/2018
 * Time: 17:01
 */

namespace SlimApp\model\UseCase;


class CheckFileIdUserUseCase
{
    private $repository;

    /**
     * CheckFileIdUserUseCase constructor.
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }
    public function __invoke($file_id) {



        $info = $this->repository->checkfilesId($file_id);

        return $info;

    }

}