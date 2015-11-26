<?php

return CMap::mergeArray(
	(require __DIR__ . '/base.php'),
	(file_exists(__DIR__ . '/local.php') ? require(__DIR__ . '/local.php') : array())
);
