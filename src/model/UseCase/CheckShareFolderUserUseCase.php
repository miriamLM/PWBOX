<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 17/05/2018
 * Time: 10:16
 */

namespace SlimApp\model\UseCase;


class CheckShareFolderUserUseCase
{
private $repository;

    /**
     * CheckShareFolderUserUseCase constructor.
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }
    public function __invoke($folder_id,$id_owner) {



        $info = $this->repository->checksharefolders($folder_id,$id_owner);

        return $info;

    }

}