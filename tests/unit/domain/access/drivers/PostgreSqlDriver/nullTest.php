<?php
namespace test\domain\access\drivers\PostgreSqlDriver;

use orm\domain\access\drivers\PostgreSqlDriver;

class nullTest extends \BaseTestCase
{
	/**
	 * @var PostgreSqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new PostgreSqlDriver();
	}

	public function test_IsNULL() {
		$this->assertEquals(' NULL', $this->driver->_NULL());
	}

	public function test_IsNotNULL() {
		$this->assertEquals(' NOT NULL', $this->driver->_NULL(false));
	}
}
