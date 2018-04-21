<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 15/5/18
 * Time: 18:30
 */

namespace SlimApp\model\UseCase;


class CheckUserFolderUseCase
{
    private $repository;

    /**
     * CheckUserFolderUseCase constructor.
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function __invoke($folder_id)
    {
        $info = $this->repository->checkUserFolder($folder_id);

        return $info;
    }


}