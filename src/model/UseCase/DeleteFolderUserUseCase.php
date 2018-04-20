<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 12/05/2018
 * Time: 16:56
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class DeleteFolderUserUseCase
{


    private $repository;

    public function __construct(bbddRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($folder_id) {



        $this->repository->deletefolder($folder_id);
    }

}