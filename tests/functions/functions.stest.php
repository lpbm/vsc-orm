<?php
class Functions extends Snap_UnitTestCase {

	private $state;

	public function setUp () {
		$this->state = get_include_path();
	}

	public function tearDown() {
		set_include_path($this->state);
	}

	public function testIsCli() {
		return $this->assertEqual (isCli(), (php_sapi_name() == 'cli'));
	}

	public function testCleanBuffers() {
		ob_start ();
		echo 'buff2';
		ob_start ();
		echo 'buff1';

		$s = cleanBuffers();

		return $this->assertTrue((ob_get_level() == 0) && ($s == 'buff1buff2'));
	}

	public function testAddPath() {
		addPath(dirname(__FILE__), '');

		return $this->assertEqual(get_include_path(), dirname(__FILE__) . PATH_SEPARATOR);
	}

	/**
	 * This tests the importing of a package without exceptions
	 * @return unknown_type
	 */
	public function testImportWithOutExceptionsReturnPath () {
		set_include_path ('.');
		import ( _LIB_PATH); // this should exist at all times
		$sTestPath = substr ( _LIB_PATH,0,-1). PATH_SEPARATOR . '.';
		return $this->assertEqual (get_include_path(), $sTestPath);
	}


	public function testImportWithExceptionsReturnPath () {
		set_include_path ('.');
		import ( _LIB_PATH); // this should exist at all times
		import ('infrastructure'); // this should exist at all times and have exceptions
		$sTestPath = _LIB_PATH . 'infrastructure'. PATH_SEPARATOR . substr ( _LIB_PATH,0,-1).PATH_SEPARATOR.'.';

		return $this->assertEqual (get_include_path(), $sTestPath);
	}

	public function testImportBadPackage () {
		$e = 0;
		$sPackageName = '...';

		$this->willThrow('vscExceptionPackageImport');
		import ($sPackageName);
	}
}
