<?php
namespace tests\domain\access\clauses\SqlClauseAccess;

use orm\domain\access\clauses\SqlClauseAccess;
use orm\domain\access\drivers\SqlGenericDriver;

class getGrammarHelperTest extends \BaseTestCase
{

	public function testBasicGetConnection() {
		$drv = $this->getMockBuilder(SqlGenericDriver::class)->getMock();

		$o = new SqlClauseAccess();
		$o->setGrammarHelper($drv);

		$this->assertInstanceOf(SqlGenericDriver::class, $o->getGrammarHelper());
		$this->assertSame($drv, $o->getGrammarHelper());
	}
}