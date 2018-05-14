<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 14/05/2018
 * Time: 21:24
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class CheckFoldersSharedUserUseCase
{
    /** @var bbddRepository */
    private $repository;

    /**
     * CheckFoldersSharedUserUseCase constructor.
     * @param bbddRepository $repository
     */
    public function __construct(bbddRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($id_shared) {


        $info = $this->repository->checkFoldersShared($id_shared);

        return $info;

    }

}