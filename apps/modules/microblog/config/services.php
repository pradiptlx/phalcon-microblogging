<?php

use Dex\microblog\Infrastructure\Persistence\SqlRoleRepository;
use Dex\microblog\Infrastructure\Persistence\SqlUserRepository;
use Phalcon\Mvc\View;

$di['view'] = function () {
    $view = new View();
    $view->setViewsDir(__DIR__ . '/../Presentation/Web/views/');

    $view->registerEngines(
        [
            ".volt" => "voltService",
        ]
        );

    return $view;
};

$di->setShared('sqlUserRepository', function (){
   return new SqlUserRepository($this);
});

$di->setShared('sqlRoleRepository', function (){
    return new SqlRoleRepository($this);
});
