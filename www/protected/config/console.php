<?php

return CMap::mergeArray(
    (require __DIR__ . '/base.php'),
    (require __DIR__ . '/base-console.php'),
    (file_exists(__DIR__ . '/local.php') ? require(__DIR__ . '/local.php') : array())
);


// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.

