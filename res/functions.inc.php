<?php
/**
 * Function to turn the triggered errors into exceptions
 * @author troelskn at gmail dot com
 * @see http://php.net/manual/en/class.errorexception.php
 * @param $severity
 * @param $message
 * @param $filename
 * @param $lineno
 * @throws \vsc\ExceptionError
 * @return void
 */
function exceptions_error_handler ($iSeverity, $sMessage, $sFilename, $iLineNo) {
	if (error_reporting() == 0) {
		return;
	}

	if (error_reporting() & $iSeverity) {
		// the __autoload seems not to be working here
		throw new \vsc\ExceptionError ($sMessage, 0, $iSeverity, $sFilename, $iLineNo);
	}
}

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

function getPaths () {
	return explode (PATH_SEPARATOR, get_include_path());
}

function cleanBuffers ($iLevel = null) {
	$sErrors = '';
	if (is_null($iLevel))
		$iLevel = ob_get_level();

		for ($i = 0; $i < $iLevel; $i++) {
		$sErrors .= ob_get_clean();
	}
	return $sErrors;
}

function getDirFiles ( $dir, $showHidden = false){
	$files =  array();
	if (!is_dir($dir)){
		trigger_error('Can not find : '.$dir);
		return false;
	}

	if ( $root = @opendir($dir) ){
		while ($file = readdir ($root)){
			if ( ($file == '.' || $file == '..') || ($showHidden == false && stripos($file, '.') === 0)){continue;}

			if (substr($dir, -1) != '/') $dir .= '/';

			if( is_dir ($dir . $file) ){
				$files = array_merge($files, getDirFiles($dir . $file));
			} else {
				/*if ( stristr($file, 'tpl') )*/ $files[] = $dir . $file;
			}
		}
	}
	return $files;
}

if (!function_exists('_e')) {
	function getErrorHeaderOutput ($e = null) {
		header ('HTTP/1.1 500 Internal Server Error');
		$sRet = '<?xml version="1.0" encoding="utf-8"?>';
		$sRet .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"';
		$sRet .= '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		$sRet .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
		$sRet .= '<head>';
		$sRet .= '<style>ul {padding:0; font-size:0.8em} li {padding:0.2em;display:inline} address {position:fixed;bottom:0;}</style>';
		$sRet .= '<title>Internal Error' . (!$e ? '' : ': '. substr($e->getMessage(), 0, 20) . '...') . '</title>';
		$sRet .= '</head>';
		$sRet .= '<body>';
		$sRet .= '<strong>Internal Error' . (!$e ? '' : ': '. $e->getMessage()) . '</strong>';
		$sRet .= '<address>&copy; VSC</address>';
		$sRet .= '<ul><li><a href="#" onclick="p = document.getElementById(\'trace\'); if (p.style.display==\'block\') p.style.display=\'none\';else p.style.display=\'block\'; return false">toggle trace</a></li><li><a href="javascript: p = document.getElementById(\'trace\'); document.location.href =\'mailto:'.ROOT_MAIL.'?subject=Problems&body=\' + p.innerHTML; return false">mail me</a></li></ul>';

		if ($e instanceof Exception)
			$sRet .= '<p style="font-size:.8em">Triggered in <strong>' . $e->getFile() . '</strong> at line ' . $e->getLine() .'</p>';

		$sRet .= '<pre style="position:fixed;bottom:2em;display:none;font-size:.8em" id="trace">';

		return $sRet;
	}

	function _e ($e) {
		$sErrors = cleanBuffers();
		header ('HTTP/1.1 500 Internal Server Error');
		echo getErrorHeaderOutput ($e);
		if (isDebug()) {
			echo $e ? $e->getTraceAsString() : '';
		}
		if ($sErrors)
		echo '<p>' . $sErrors . '</p>';
		echo '</pre>';
		echo '</body>';
		echo '</html>';
		exit (0);
	}
}
