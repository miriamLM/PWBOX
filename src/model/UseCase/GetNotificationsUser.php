<?php
/**
 * Created by PhpStorm.
 * User: carmeperseguerbarragan
 * Date: 17/5/18
 * Time: 19:51
 */

namespace SlimApp\model\UseCase;


use SlimApp\model\Interfaces\bbddRepository;

class GetNotificationsUser
{
    private $repository;


    public function __construct(bbddRepository $repository)
    {
        $this->repository = $repository;
    }


    public function __invoke() {


        $notifications = $this->repository->getnotificationsuser();

        return $notifications;

    }
}