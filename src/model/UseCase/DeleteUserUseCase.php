<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 8/5/18
 * Time: 19:37
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class DeleteUserUseCase
{

    /** @var bbddRepository */
    private $repository;

    public function __construct(bbddRepository $repository){

        $this->repository= $repository;

    }

    public function __invoke( $id) {

        /**
         * Elimina usuario , del id que ha iniciado session
         **/

        $stmt = $this->repository->delete($id);


        return $stmt;


    }

}