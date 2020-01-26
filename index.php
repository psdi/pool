<?php

declare(strict_types=1);

$config = require 'config/config.php';
// use Composer Autoloader
require dirname(__FILE__) . '/vendor/autoload.php';
// use own Autoloader
require 'config/autoload.php';

$container = (require 'config/container.php')($config);

$front = new Pool\Controller\FrontController($container);
$front->run();
