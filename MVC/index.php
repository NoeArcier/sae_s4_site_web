<?php
/*
 * yasmf - Yet Another Simple MVC Framework (For PHP)
 *     Copyright (C) 2023   Franck SILVESTRE
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU Affero General Public License as published
 *     by the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU Affero General Public License for more details.
 *
 *     You should have received a copy of the GNU Affero General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/*
 * Sample without database connexion
 */
const PREFIX = "/mvc";
require $_SERVER[ 'DOCUMENT_ROOT' ] . PREFIX . '/lib/vendor/autoload.php';


use application\DefaultComponentFactory;
use yasmf\Router;
use yasmf\DataSource

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
