<?php

use Phalcon\Mvc\Router;

/**
 * @var Router $router
 */


// $router->add('/:params', array(
//     'namespace' => $module['webControllerNamespace'],
//     'module' => $moduleName,
//     'controller' => isset($module['defaultController']) ? $module['defaultController'] : 'index',
//     'action' => isset($module['defaultAction']) ? $module['defaultAction'] : 'index',
//     'params'=> 1
// ));

// $router->add('/:controller/:params', array(
//     'namespace' => $module['webControllerNamespace'],
//     'module' => $moduleName,
//     'controller' => 1,
//     'action' => isset($module['defaultAction']) ? $module['defaultAction'] : 'index',
//     'params' => 2
// ));

// $router->add('/:controller/:action/:params', array(
//     'namespace' => $module['webControllerNamespace'],
//     'module' => $moduleName,
//     'controller' => 1,
//     'action' => 2,
//     'params' => 3
// ));

/**
 * @var string $moduleName
 * @var array $module
 */

$router->add(
    '/home',
    [
        'namespace' => $module['webControllerNamespace'],
        'module' => 'microblog',
        'controller' => 'post',
        'action' => 'index'
    ]
);
$router->add(
    '/post',
    [
        'namespace' => $module['webControllerNamespace'],
        'module' => 'microblog',
        'controller' => 'post',
        'action' => 'index'
    ]
);
$router->add(
    '/user',
    [
        'namespace' => $module['webControllerNamespace'],
        'module' => 'microblog',
        'controller' => 'user',
        'action' => 'dashboard'
    ]
);
/**
 * USER
 */
$userRouter = new Router\Group([
    'namespace' => $module['webControllerNamespace'],
    'module' => 'microblog',
    'controller' => 'user'
]);
$userRouter->setPrefix('/user');
$userRouter->add(
    '/findUser/:params',
    [
        'action' => 'findUser',
        'params' => 1
    ]
)->setName('find-user');
$userRouter->add(
    '/:action',
    [
        'action' => 1
    ]
);
$userRouter->add(
    '/:action/:params',
    [
        'action' => 1,
        'params' => 2
    ]
);

/**
 * Post
 */
$postRouter = new Router\Group([
    'namespace' => $module['webControllerNamespace'],
    'module' => 'microblog',
    'controller' => 'post'
]);
$postRouter->setPrefix('/post');
$postRouter->add(
    '/',
    [
        'action' => 'index'
    ]
)->setName('home');
$postRouter->add(
    '/createPost',
    [
        'action' => 'createPost'
    ]
)->setName('create-post');
$postRouter->add(
    '/viewPost/:params',
    [
        'action' => 'viewPost',
        'params' => 1
    ]
)->setName('view-post');
$postRouter->add(
    '/editPost/:params',
    [
        'action' => 'editPost',
        'params' => 1
    ]
)->setName('edit-post');
$postRouter->add(
    '/{postId}/deletePost',
    [
        'action' => 'deletePost',
        'postId' => 1
    ]
)->setName('delete-post');
$postRouter->add(
    '/:params/replyPost',
    [
        'action' => 'replyPost',
        'params' => 1
    ]
)->setName('reply-post');
$postRouter->add(
    '/{postId}/replyPost/{replyId}/:params',
    [
        'action' => 'replyOfReply',
        'postId' => 1,
        'replyId' => 2,
        'params' => 3
    ]
)->setName('reply-reply');

/**
 * File
 */
//$fileRouter = new Router\Group([
//    'controller' => 'fileManager'
//]);

//var_dump($postRouter->getRoutes());
//die();

$router->mount($userRouter);
$router->mount($postRouter);

