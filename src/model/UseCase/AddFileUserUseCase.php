<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 9/5/18
 * Time: 19:33
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class AddFileUserUseCase
{

    /** @var bbddRepository */
    private $repository;

    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke(array $file, $id,$id_folder) {


        $this->repository->addfile($file,$id,$id_folder);


    }





}