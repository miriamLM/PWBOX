<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 10/5/18
 * Time: 20:36
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class CheckFileUserUseCase
{
    /** @var bbddRepository */
    private $repository;

    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke() {



        $info = $this->repository->checkfiles();

        return $info;

    }


}