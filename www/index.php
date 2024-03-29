<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);

define('ROOT_DIR', realpath(__DIR__ . DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR));
if (file_exists(ROOT_DIR . '/vendor/autoload.php'))
    require ROOT_DIR . '/vendor/autoload.php';

Yii::setPathOfAlias('root', ROOT_DIR);
Yii::createWebApplication($config)->run();
