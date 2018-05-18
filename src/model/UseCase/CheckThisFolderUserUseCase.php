<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 18/05/2018
 * Time: 9:27
 */

namespace SlimApp\model\UseCase;


class CheckThisFolderUserUseCase
{
    private $repository;

    /**
     * CheckThisFolderUserUseCase constructor.
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }
    public function __invoke($folder_id)
    {
        $info = $this->repository->checkThisFolder($folder_id);

        return $info;
    }

}