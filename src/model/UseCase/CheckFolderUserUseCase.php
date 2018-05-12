<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 12/05/2018
 * Time: 16:08
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class CheckFolderUserUseCase
{


    /**
     * CheckFolderUserUseCase constructor.
     */
    private $repository;

    public function __construct(bbddRepository $repository)
    {
        $this->repository= $repository;
    }
    public function __invoke()
    {
        $info = $this->repository->checkfolders();

        return $info;
    }
}