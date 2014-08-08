<?php
/**
 * @package unit_tests
 * @subpackage PHPUnit
 *
 * Bolierplate code to make PHPUnit play nice with vsc-orm
 */
if (!defined('VSC_PATH')) {
	define ( 'VSC_PATH', realpath( dirname (__FILE__) . '/../../vsc-v2') . DIRECTORY_SEPARATOR );
	set_include_path ( _PATH . PATH_SEPARATOR . get_include_path());
	require ('vsc.inc.php');
}

if (!defined('ORM_PATH')) {
	define ( 'ORM_PATH', realpath( dirname (__FILE__) . '/../') . DIRECTORY_SEPARATOR );
	set_include_path (ORM_PATH . PATH_SEPARATOR . get_include_path());
	require ('orm.inc.php');

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

if (!defined('PHPUNIT_PATH')) {
	define ('PHPUNIT_PATH', '/usr/share/pear/PHPUnit/');
	set_include_path (PHPUNIT_PATH . PATH_SEPARATOR . get_include_path());
	require (PHPUNIT_PATH . 'Autoload.php');
}

