<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 16/05/2018
 * Time: 20:32
 */

namespace SlimApp\model\UseCase;


class DeleteShareUserUsecase
{
    private $repository;

    /**
     * DeleteShareUserUsecase constructor.
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($id_folder)
    {
        $this->repository->deleteshare($id_folder);
    }

}