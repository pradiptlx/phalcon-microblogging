<?php

use Dex\Microblog\Core\Application\Service\CreatePostService;
use Dex\Microblog\Core\Application\Service\CreateReplyService;
use Dex\Microblog\Core\Application\Service\CreateUserAccountService;
use Dex\Microblog\Core\Application\Service\DeletePostService;
use Dex\Microblog\Core\Application\Service\GetAllFilesService;
use Dex\Microblog\Core\Application\Service\ShowAllPostService;
use Dex\Microblog\Core\Application\Service\ShowDashboardService;
use Dex\Microblog\Core\Application\Service\UserLoginService;
use Dex\Microblog\Core\Application\Service\ViewPostService;
use Dex\Microblog\Core\Application\Service\ViewReplyByPostService;
use Dex\Microblog\Infrastructure\Persistence\SqlFileRepository;
use Dex\Microblog\Infrastructure\Persistence\SqlPostRepository;
use Dex\Microblog\Infrastructure\Persistence\SqlReplyPostRepository;
use Dex\Microblog\Infrastructure\Persistence\sqlUserRepository;

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

$di->set('sqlFileManagerRepository', function () {
    return new SqlFileRepository();
});

$di->set('sqlReplyPostRepository', function () {
    return new SqlReplyPostRepository();
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

$di->set('createUserAccountService', function () use ($di) {
    return new CreateUserAccountService($di->get('sqlUserRepository'));
});

$di->set('createPostService', function () use ($di) {
    return new CreatePostService(
        $di->get('sqlPostRepository'),
        $di->get('sqlFileManagerRepository')
    );
});

$di->set('deletePostService', function () use ($di) {
    return new DeletePostService(
        $di->get('sqlPostRepository'),
        $di->get('sqlReplyPostRepository'),
        $di->get('sqlFileManagerRepository')
    );
});

$di->set('showDashboardService', function () use ($di) {
    return new ShowDashboardService($di->get('sqlPostRepository'), $di->get('sqlUserRepository'));
});

$di->set('viewPostService', function () use ($di) {
    return new ViewPostService(
        $di->get('sqlPostRepository'),
        $di->get('sqlFileManagerRepository')
    );
});

$di->set('viewReplyByPostService', function () use ($di) {
    return new ViewReplyByPostService(
        $di->get('sqlReplyPostRepository')
    );
});

$di->set('createReplyService', function () use($di){
    return new CreateReplyService(
        $di->get('sqlReplyPostRepository'),
        $di->get('sqlPostRepository')
    );
});

