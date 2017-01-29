<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class asTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_ASEmptyFields() {
		$this->assertEquals('', $this->driver->_AS(''));
	}

	public function test_ASTable() {
		$alias = uniqid('test:');
		$this->assertEquals(' AS ' . $alias, $this->driver->_AS($alias));
	}
}
