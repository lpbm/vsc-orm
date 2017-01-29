<?php
namespace tests\domain\access\clauses\SqlClauseAccess;

use orm\domain\access\clauses\SqlClauseAccess;
use orm\domain\connections\ConnectionA;

class setConnectionTest extends \BaseTestCase
{

	public function testBasicSetConnection() {
		$mockConnection = $this->getMockBuilder(ConnectionA::class)->getMock();

		$o = new SqlClauseAccess();
		$o->setConnection($mockConnection);

		$r = new \ReflectionObject($o);
		$rProp = $r->getProperty('oConnection');
		$rProp->setAccessible(true);
		$con = $rProp->getValue($o);

		$this->assertInstanceOf(ConnectionA::class, $con);
		$this->assertSame($mockConnection, $con);
	}
}