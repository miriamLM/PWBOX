<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 16/5/18
 * Time: 12:30
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class CapacityUserUseCase
{
    private $repository;


    public function __construct(bbddRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke() {


        $capacity = $this->repository->capacityuser();
        return $capacity;

    }
}