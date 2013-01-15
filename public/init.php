<?php

set_time_limit(0);

define('ROOT', dirname(str_replace('\\', '/', __FILE__)) . '/');

define('FUNC', ROOT . 'function/');
define('CLS', ROOT . 'class/');
define('LOG', ROOT . 'log/CustomLog/' . date("Y-m-d") . '.log');
define('ERRORLOG', ROOT . 'log/error.txt');
define('AD_DIR', ROOT . 'data/advertise/');

define('TIME', time());

include 'config.php';

include FUNC . 'function.php';
