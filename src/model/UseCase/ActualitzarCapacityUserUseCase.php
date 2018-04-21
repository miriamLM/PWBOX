<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 16/5/18
 * Time: 12:39
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class ActualitzarCapacityUserUseCase
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


    public function __invoke($file_size) {


        $this->repository->restarcapacityuser($file_size);

    }
}