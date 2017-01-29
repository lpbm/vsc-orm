<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class nullTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_IsNULL() {
		$this->assertEquals(' NULL', $this->driver->_NULL());
	}

	public function test_IsNotNULL() {
		$this->assertEquals(' NOT NULL', $this->driver->_NULL(false));
	}
}
