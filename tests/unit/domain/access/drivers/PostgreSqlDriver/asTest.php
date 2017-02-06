<?php
namespace test\domain\access\drivers\PostgreSqlDriver;

use orm\domain\access\drivers\PostgreSqlDriver;

class asTest extends \BaseTestCase
{
	/**
	 * @var PostgreSqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new PostgreSqlDriver();
	}

	public function test_ASEmptyFields() {
		$this->assertEquals('', $this->driver->_AS(''));
	}

	public function test_ASTable() {
		$alias = uniqid('test:');
		$this->assertEquals(' AS "' . $alias . '" ', $this->driver->_AS($alias));
	}
}
