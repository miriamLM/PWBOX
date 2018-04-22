<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 17/05/2018
 * Time: 11:03
 */

namespace SlimApp\model\UseCase;


class AddInsideShareFolderUserUseCase
{
    private $repository;

    /**
     * AddInsideShareFolderUserUseCase constructor.
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($id_owner,$folder_name,$id_parent)
    {
        [$num_folders, $info] = $this->repository->newFolderInsideShare($id_owner,$folder_name,$id_parent);
        return [$num_folders,$info];
    }

}