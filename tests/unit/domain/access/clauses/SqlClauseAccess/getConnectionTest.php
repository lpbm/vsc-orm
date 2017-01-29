<?php
namespace tests\domain\access\clauses\SqlClauseAccess;

use orm\domain\access\clauses\SqlClauseAccess;
use orm\domain\connections\ConnectionA;

class getConnectionTest extends \BaseTestCase
{

	public function testBasicGetConnection() {
		$mockConnection = $this->getMockBuilder(ConnectionA::class)->getMock();

		$o = new SqlClauseAccess();
		$o->setConnection($mockConnection);

		$this->assertInstanceOf(ConnectionA::class, $o->getConnection());
		$this->assertSame($mockConnection, $o->getConnection());
	}
}