<?php

use Dex\Microblog\Core\Application\Service\UserLoginService;
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

$di->set('userLoginService', function () use ($di) {
    return new UserLoginService($di->get('sqlUserRepository'));
});
