<?php
namespace test\domain\access\drivers\PostgreSqlDriver;

use orm\domain\access\drivers\PostgreSqlDriver;

class orderTest extends \BaseTestCase
{
	/**
	 * @var PostgreSqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new PostgreSqlDriver();
	}

	public function test_ORDEREmpty() {
		$this->assertEquals('', $this->driver->_ORDER());
	}

	public function test_ORDERBy() {
		$by = uniqid('test:');
		$this->assertEquals(sprintf(' ORDER BY "%s" ', $by), $this->driver->_ORDER($by));
	}
}
