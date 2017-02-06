<?php
namespace test\domain\access\drivers\PostgreSqlDriver;

use orm\domain\access\drivers\PostgreSqlDriver;

class groupTest extends \BaseTestCase
{
	/**
	 * @var PostgreSqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new PostgreSqlDriver();
	}

	public function test_GROUPEmpty() {
		$this->assertEquals('', $this->driver->_GROUP());
	}

	public function test_GROUPBy() {
		$by = uniqid('test:');
		$this->assertEquals(' GROUP BY "' . $by .'" ', $this->driver->_GROUP($by));
	}
}
