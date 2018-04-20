<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 14/5/18
 * Time: 18:53
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class AddShareUserUseCase
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


    public function __invoke($id_owner,$id_usershared,$id_folder,$type) {


        $this->repository->addshareuser($id_owner,$id_usershared,$id_folder,$type);


    }

}