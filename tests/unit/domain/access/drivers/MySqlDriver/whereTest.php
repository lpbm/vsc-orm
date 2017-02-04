<?php
namespace test\domain\access\drivers\MySqlDriver;

use orm\domain\access\drivers\MySqlDriver;

class whereTest extends \BaseTestCase
{
	/**
	 * @var MySqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new MySqlDriver();
	}

	public function test_WHEREEmpty() {
		$this->assertEquals('', $this->driver->_WHERE());
	}

	public function test_WHEREWithClause() {
		$clause = uniqid('test:');
		$this->assertEquals(' WHERE ' . $clause, $this->driver->_WHERE($clause));
	}
}
