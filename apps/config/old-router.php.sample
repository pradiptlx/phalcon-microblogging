<?php

use Phalcon\Mvc\Router;

/**
 * @var Router $router
 */
$router = $di->getShared('router');

$router->add(
    '/home',
    [
        'controller' => 'post',
        'action' => 'index'
    ]
);

/**
 * USER
 */
$userRouter = new Router\Group([
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
$fileRouter = new Router\Group([
    'controller' => 'fileManager'
]);


$router->mount($userRouter);
$router->mount($postRouter);

$router->handle($_SERVER['REQUEST_URI']);
