<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 16/5/18
 * Time: 15:53
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class FileSizeUserUseCase
{
    private $repository;


    public function __construct(bbddRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke($file_id) {


        $filesize = $this->repository->filesize($file_id);

        return $filesize;

    }

}