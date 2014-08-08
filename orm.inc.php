<?php
if (!defined ('ORM_PATH')) {
	define ('ORM_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
}
require (ORM_PATH . 'res'. DIRECTORY_SEPARATOR .'config.inc.php');
//require (ORM_PATH . 'res'. DIRECTORY_SEPARATOR .'functions.inc.php');

if ( !defined ('VSC_PATH') ) {
	$sMessage = 'libVSC is required in order to load this ORM framework';
	throw new ErrorException ($sMessage, E_USER_ERROR);
} else {
	include_once ( _PATH . 'vsc.inc.php');

	import (ORM_LIB_PATH);
	import (ORM_RES_PATH);
}

if (!defined ('ROOT_MAIL')) {
	if (!isCli()) {
		define ('ROOT_MAIL', 'root@' . $_SERVER['HTTP_HOST']);
	} else {
		define ('ROOT_MAIL', 'root@localhost');
	}
}
