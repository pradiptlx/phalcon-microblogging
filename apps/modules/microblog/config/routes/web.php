<?php


$router->add('/:params', array(
    'namespace' => $module['webControllerNamespace'],
    'module' => $moduleName,
    'controller' => isset($module['defaultController']) ? $module['defaultController'] : 'index',
    'action' => isset($module['defaultAction']) ? $module['defaultAction'] : 'index',
    'params'=> 1
));

$router->add('/:controller/:params', array(
    'namespace' => $module['webControllerNamespace'],
    'module' => $moduleName,
    'controller' => 1,
    'action' => isset($module['defaultAction']) ? $module['defaultAction'] : 'index',
    'params' => 2
));

$router->add('/:controller/:action/:params', array(
    'namespace' => $module['webControllerNamespace'],
    'module' => $moduleName,
    'controller' => 1,
    'action' => 2,
    'params' => 3
));

?>