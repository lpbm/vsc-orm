<?php
namespace tests\domain\access\clauses\SqlClauseAccess;

use orm\domain\access\clauses\SqlClauseAccess;
use orm\domain\access\drivers\SqlGenericDriver;

class setGrammarHelperTest extends \BaseTestCase
{

	public function testBasicSetGrammarHelper() {
		$drv = $this->getMockBuilder(SqlGenericDriver::class)->getMock();

		$o = new SqlClauseAccess();
		$o->setGrammarHelper($drv);

		$r = new \ReflectionObject($o);
		$rProp = $r->getProperty('oGrammarHelper');
		$rProp->setAccessible(true);
		$con = $rProp->getValue($o);

		$this->assertInstanceOf(SqlGenericDriver::class, $con);
		$this->assertSame($drv, $con);
	}
}