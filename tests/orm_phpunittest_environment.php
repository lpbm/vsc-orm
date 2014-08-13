<?php
/**
 * @package unit_tests
 * @subpackage PHPUnit
 *
 * Bolierplate code to make PHPUnit play nice with vsc-orm
 */

if (!defined('ORM_PATH')) {
	define ( 'ORM_PATH', realpath( dirname (__FILE__) . '/../') . DIRECTORY_SEPARATOR );
	set_include_path (ORM_PATH . PATH_SEPARATOR . get_include_path());
//	require ('orm.inc.php');

	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

chdir(dirname(__FILE__) . '/../');
// Composer autoloading.
if ( file_exists('vendor/autoload.php') ) {
    $loader = include_once 'vendor/autoload.php';
} else {
    throw new RuntimeException('Unable to load the autoloader. Run `php composer.phar install`.');
}
