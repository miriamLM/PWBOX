<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 20/4/18
 * Time: 11:04
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;
use SlimApp\model\User;


class CheckUserUseCase
{
    /** @var bbddRepository */
    private $repository;

    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke($id) {

        /**
         * Me recoge toda la info del usuario , del id que ha iniciado session
         **/
        $usuari = $this->repository->check($id);

        /**
         * 
         **/



        return $usuari;

    }


}