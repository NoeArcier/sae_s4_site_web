<?php

const PREFIX = "/mvc";
require $_SERVER[ 'DOCUMENT_ROOT' ] . '/lib/vendor/autoload.php';


use application\DefaultComponentFactory;
use yasmf\Router;
use yasmf\DataSource;

$dbConfig = require 'dbconfig.php';

$data_source = new DataSource(
    $dbConfig['db_host'],
    $dbConfig['db_port'], 
    $dbConfig['db_name'], 
    $dbConfig['db_user'], 
    $dbConfig['db_pass'], 
    $dbConfig['db_charset']
);

$router = new Router(new DefaultComponentFactory()) ;
$router->route(PREFIX, $data_source);
