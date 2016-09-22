<?php
$baseDir = dirname(__DIR__);
$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Kubikvest', array($baseDir.'/src/', $baseDir.'/tests/'));
$loader->register();
date_default_timezone_set('UTC');
