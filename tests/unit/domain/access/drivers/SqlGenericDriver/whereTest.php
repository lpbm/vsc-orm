<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class whereTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_WHEREEmpty() {
		$this->assertEquals('', $this->driver->_WHERE());
	}

	public function test_WHEREWithClause() {
		$clause = uniqid('test:');
		$this->assertEquals(' WHERE ' . $clause, $this->driver->_WHERE($clause));
	}
}
