<?php

use Phalcon\Config;
use Phalcon\Escaper;
use Phalcon\Session\Adapter\Stream as SessionAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Security;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Phalcon\Mvc\View;
use Phalcon\Mvc\ViewBaseInterface;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Flash\Direct as Flash;
use Phalcon\Flash\Session as FlashSession;

/**
 * @var Config $config
 * @return mixed
 */
$container['config'] = function () use ($config) {
    return $config;
};

$container->setShared('session', function () {
    $session = new SessionManager();
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir(),
    ]);
    $session->setAdapter($files);
    $session->start();

    return $session;
});

$container['dispatcher'] = function () {

    $eventsManager = new Manager();

    $eventsManager->attach(
        'dispatch:beforeException',
        function (Event $event, $dispatcher, Exception $exception) {
            // 404
            if ($exception instanceof \Phalcon\Mvc\Dispatcher\Exception) {
                $dispatcher->forward(
                    [
                        'controller' => 'index',
                        'action' => 'fourOhFour',
                    ]
                );

            }
            return false;
        }
    );

    $dispatcher = new Dispatcher();
//    $dispatcher->setDefaultNamespace(
//        'Dex\Microblog\Presentation\Web\Controller'
//    );
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;
};

$container['url'] = function () use ($config) {
    $url = new \Phalcon\Url();

    $url->setBaseUri($config->url['baseUrl']);

    return $url;
};

$container['voltService'] = function (ViewBaseInterface $view) use ($container, $config) {

    $volt = new Volt($view, $container);

    if (!is_dir($config->application->cacheDir)) {
        mkdir($config->application->cacheDir);
    }

    $compileAlways = $config->mode == 'DEVELOPMENT';

    $volt->setOptions(
        [
            'always' => $compileAlways,
            'extension' => '.php',
            'separator' => '_',
            'stat' => true,
            'path' => $config->application->cacheDir,
            'prefix' => '-prefix-',
        ]
    );

    return $volt;
};

$container['view'] = function () {
    $view = new View();
    $view->setViewsDir(APP_PATH . '/common/views/');

    $view->registerEngines(
        [
            ".volt" => "voltService",
        ]
    );

    return $view;
};

$container->set(
    'security',
    function () {
        $security = new Security();
        $security->setWorkFactor(12);

        return $security;
    },
    true
);

$container->set(
    'flash',
    function () {
        $escaper = new Escaper();
        $flash = new Flash($escaper);
        $flash->setImplicitFlush(false);
        $flash->setCssClasses([
            'error' => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice' => 'alert alert-info',
            'warning' => 'alert alert-warning'
        ]);

        return $flash;
    });

/**
 * Change Flash session css Classes
 */
$container->set('flashSession', function (){
    $escaper = new Escaper();
    $flash = new FlashSession($escaper);
    $flash->setCssClasses([
        'error' => 'alert alert-danger alert-dismissible fade show',
        'success' => 'alert alert-success alert-dismissible fade show',
        'notice' => 'alert alert-info alert-dismissible fade show',
        'warning' => 'alert alert-warning alert-dismissible fade show'
    ]);

    return $flash;
});

$container['db'] = function () use ($config) {

    $dbAdapter = $config->database->adapter;

    return new $dbAdapter([
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->dbname
    ]);
};
