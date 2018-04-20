<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 14/5/18
 * Time: 19:37
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class FoldersSharedUserUseCase
{
    private $repository;


    public function __construct(bbddRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke($id_usershared) {


        $folders = $this->repository->foldershareduser($id_usershared);

        return $folders;

    }

}