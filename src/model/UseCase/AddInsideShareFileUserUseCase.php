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
<<<<<<< HEAD
    private $repository;
=======
    private $reporitory;
>>>>>>> ace971476eeba1afaa30548c4067ac380b28680b

    /**
     * AddInsideShareFileUserUseCase constructor.
     * @param $reporitory
     */
<<<<<<< HEAD
    public function __construct($repository)
    {
        $this->repository = $repository;
=======
    public function __construct($reporitory)
    {
        $this->reporitory = $reporitory;
>>>>>>> ace971476eeba1afaa30548c4067ac380b28680b
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