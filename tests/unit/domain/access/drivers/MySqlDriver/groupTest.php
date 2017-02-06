<?php
namespace test\domain\access\drivers\MySqlDriver;

use orm\domain\access\drivers\MySqlDriver;

class groupTest extends \BaseTestCase
{
	/**
	 * @var MySqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new MySqlDriver();
	}

	public function test_GROUPEmpty() {
		$this->assertEquals('', $this->driver->_GROUP());
	}

	public function test_GROUPBy() {
		$by = uniqid('test:');
		$this->assertEquals(' GROUP BY `' . $by .'` ', $this->driver->_GROUP($by));
	}
}
