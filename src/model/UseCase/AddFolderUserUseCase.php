<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 12/05/2018
 * Time: 12:12
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class AddFolderUserUseCase
{
    private $repository;


    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke($id,$folder_name,$id_parent)
    {
        [$num_folders, $info] = $this->repository->newFolder($id,$folder_name,$id_parent);
        return [$num_folders,$info];
    }

}