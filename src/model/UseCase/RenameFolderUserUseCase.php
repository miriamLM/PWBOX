<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 12/05/2018
 * Time: 16:00
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class RenameFolderUserUseCase
{
    private $repository;

    /**
     * RenameFolderUserUseCase constructor.
     * @param $repository
     */
    public function __construct(bbddRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke($name,$new_name) {


        $this->repository->renamefolder($name,$new_name);

    }

}