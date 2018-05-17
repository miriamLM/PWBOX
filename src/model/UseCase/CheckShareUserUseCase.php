<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 17/05/2018
 * Time: 11:59
 */

namespace SlimApp\model\UseCase;


class CheckShareUserUseCase
{
    private $repository;

    /**
     * CheckShareUserUseCase constructor.
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }
    public function __invoke($id_folder)
    {
        $info = $this->repository->checkshare($id_folder);

        return $info;

    }


}