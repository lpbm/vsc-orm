<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class groupTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_GROUPEmpty() {
		$this->assertEquals('', $this->driver->_GROUP());
	}

	public function test_GROUPBy() {
		$by = uniqid('test:');
		$this->assertEquals(' GROUP BY ' . $by . ' ', $this->driver->_GROUP($by));
	}
}
