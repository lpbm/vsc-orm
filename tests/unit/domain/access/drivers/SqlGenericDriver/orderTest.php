<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class orderTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_ORDEREmpty() {
		$this->assertEquals('', $this->driver->_ORDER());
	}

	public function test_ORDERBy() {
		$by = uniqid('test:');
		$this->assertEquals(sprintf(' ORDER BY %s ', $by), $this->driver->_ORDER($by));
	}
}
