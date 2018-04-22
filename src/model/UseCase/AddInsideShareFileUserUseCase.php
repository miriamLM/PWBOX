<?php
/**
 * Created by PhpStorm.
 * User: len
 * Date: 17/05/2018
 * Time: 11:36
 */

namespace SlimApp\model\UseCase;


class AddInsideShareFileUserUseCase
{
    private $repository;


    public function __construct($repository)
    {
        $this->repository = $repository;

    }

    /**
     * @param array $file
     * @param $id
     * @param $id_folder
     * @param $filesize
     * @return array
     */
    public function __invoke(array $file, $id, $id_folder, $filesize) {



        [$num_items,$info] = $this->repository->addfileInsideShare($file,$id,$id_folder,$filesize);

        return [$num_items,$info];

    }

}