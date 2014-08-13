<?php
namespace orm;

function d () {
	$aRgs = func_get_args();
	$iExit = 1;

	for ($i = 0; $i < ob_get_level(); $i++) {
		// cleaning the buffers
		ob_end_clean();
	}

	if (!isCli()) {
		// not running in console
		echo '<pre>';
	}

	foreach ($aRgs as $object) {
		// maybe I should just output the whole array $aRgs
		try {
			var_dump($object);
		}catch (Exception $e) {
			//
		}
	}
	debug_print_backtrace();

	if (!isCli()) {
		// not running in console
		echo '</pre>';
	}
	exit ();
}
