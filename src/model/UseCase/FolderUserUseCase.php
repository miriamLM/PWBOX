<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 9/5/18
 * Time: 18:53
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class FolderUserUseCase
{
    private $repository;


    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke($id,$folder_name,$id_parent)
    {
        $stmt = $this->repository->newFolder($id,$folder_name,$id_parent);
        return $stmt;

    }

}