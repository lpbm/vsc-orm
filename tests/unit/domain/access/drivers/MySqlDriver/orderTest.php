<?php
namespace test\domain\access\drivers\MySqlDriver;

use orm\domain\access\drivers\MySqlDriver;

class orderTest extends \BaseTestCase
{
	/**
	 * @var MySqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new MySqlDriver();
	}

	public function test_ORDEREmpty() {
		$this->assertEquals('', $this->driver->_ORDER());
	}

	public function test_ORDERBy() {
		$by = uniqid('test:');
		$this->assertEquals(sprintf(' ORDER BY `%s` ', $by), $this->driver->_ORDER($by));
	}
}
