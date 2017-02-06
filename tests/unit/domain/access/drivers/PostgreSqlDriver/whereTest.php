<?php
namespace test\domain\access\drivers\PostgreSqlDriver;

use orm\domain\access\drivers\PostgreSqlDriver;

class whereTest extends \BaseTestCase
{
	/**
	 * @var PostgreSqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new PostgreSqlDriver();
	}

	public function test_WHEREEmpty() {
		$this->assertEquals('', $this->driver->_WHERE());
	}

	public function test_WHEREWithClause() {
		$clause = uniqid('test:');
		$this->assertEquals(' WHERE ' . $clause, $this->driver->_WHERE($clause));
	}
}
