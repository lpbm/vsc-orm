<?php
namespace test\domain\access\drivers\MySqlDriver;

use orm\domain\access\drivers\MySqlDriver;

class nullTest extends \BaseTestCase
{
	/**
	 * @var MySqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new MySqlDriver();
	}

	public function test_IsNULL() {
		$this->assertEquals(' NULL', $this->driver->_NULL());
	}

	public function test_IsNotNULL() {
		$this->assertEquals(' NOT NULL', $this->driver->_NULL(false));
	}
}
