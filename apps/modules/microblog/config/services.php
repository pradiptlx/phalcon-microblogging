<?php

use Dex\Microblog\Core\Application\Service\GetAllFilesService;
use Dex\Microblog\Core\Application\Service\ShowAllPostService;
use Dex\Microblog\Core\Application\Service\UserLoginService;
use Dex\Microblog\Infrastructure\Persistence\SqlFileRepository;
use Dex\Microblog\Infrastructure\Persistence\SqlPostRepository;
use Dex\Microblog\Infrastructure\Persistence\SqlUserRepository;
use Phalcon\Mvc\View;

$di['view'] = function () {
    $view = new View();
    $view->setViewsDir(__DIR__ . '/../Presentation/view/');

    $view->registerEngines(
        [
            ".volt" => "voltService",
        ]
    );

    return $view;
};

$di->set('sqlUserRepository', function () {
    return new SqlUserRepository($this);
});

$di->set('sqlPostRepository', function () {
    return new SqlPostRepository();
});

$di->set('sqlFileManagerRepository', function () use ($di) {
    return new SqlFileRepository();
});

$di->set('userLoginService', function () use ($di) {
    return new UserLoginService($di->get('sqlUserRepository'));
});

$di->set('showAllService', function () use ($di) {
    return new ShowAllPostService(
        $di->get('sqlPostRepository'));
});

$di->set('getAllFilesService', function () use ($di) {
    return new GetAllFilesService(
        $di->get('sqlFileManagerRepository'));
});
