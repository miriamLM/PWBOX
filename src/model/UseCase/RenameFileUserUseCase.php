<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 11/5/18
 * Time: 12:12
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class RenameFileUserUseCase
{
    /** @var bbddRepository */
    private $repository;

    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke($name,$new_name) {

        $info = $this->repository->renamefile($name,$new_name);

        return $info;
    }
}