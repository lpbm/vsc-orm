<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class andTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_AND() {
		$this->assertEquals(' AND ', $this->driver->_AND());
	}
}
