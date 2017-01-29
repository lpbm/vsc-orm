<?php
namespace tests\domain\access\clauses\SqlClauseAccess;


use orm\domain\access\clauses\SqlClauseAccess;
use orm\domain\access\drivers\SqlGenericDriver;
use orm\domain\connections\ConnectionA;
use orm\domain\domain\clauses\Clause;
use orm\domain\domain\fields\FieldA;

class getDefinitionTest extends \BaseTestCase
{
	public function testEmptyClause () {
		$mock = $this->getMockBuilder(Clause::class)->disableOriginalConstructor()->getMock();

		$o = new SqlClauseAccess();
		$this->assertEquals('', $o->getDefinition($mock));
	}

	public function testNonEmptySubject() {
		$subj = 'test';
		$subjClause = new Clause($subj);

		$o = new SqlClauseAccess();
		$this->assertEquals($subj, $o->getDefinition($subjClause));
	}

	public function testFieldSubject() {
		$subjName = 'test';
		$subj = $this->getMockBuilder(FieldA::class)->disableOriginalConstructor()->getMock();
		$subj->method('getName')->willReturn($subjName);

		$subjClause = new Clause($subj);
		$mockDriver = $this->getMockBuilder(SqlGenericDriver::class)->getMock();
		$mockConnection = $this->getMockBuilder(ConnectionA::class)->getMock();

		$o = new SqlClauseAccess();
		$o->setGrammarHelper($mockDriver);
		$o->setConnection($mockConnection);

		$this->assertEquals($subjName . ' IS NULL', $o->getDefinition($subjClause));
	}

	public function testFieldSubjectPredicative() {
		$subjName = 'test';
		$subj = $this->getMockBuilder(FieldA::class)->disableOriginalConstructor()->getMock();
		$subj->method('getName')->willReturn($subjName);

		$subjClause = new Clause($subj, '=');
		$mockDriver = $this->getMockBuilder(SqlGenericDriver::class)->getMock();
		$mockConnection = $this->getMockBuilder(ConnectionA::class)->getMock();

		$o = new SqlClauseAccess();
		$o->setGrammarHelper($mockDriver);
		$o->setConnection($mockConnection);

		$this->assertEquals($subjName . ' IS NULL', $o->getDefinition($subjClause));
	}

	public function testFieldSubjectWithValue() {
		$subjName = 'test';
		$subj = $this->getMockBuilder(FieldA::class)->disableOriginalConstructor()->getMock();
		$subj->method('getName')->willReturn($subjName);

		$value = uniqid('test:');
		$subjClause = new Clause($subj, '=', $value);

		$mockDriver = $this->getMockBuilder(SqlGenericDriver::class)->getMock();

		$mockConnection = $this->getMockBuilder(ConnectionA::class)->getMock();
		$mockConnection->method('escape')->with($value)->willReturn($value);

		$o = new SqlClauseAccess();
		$o->setGrammarHelper($mockDriver);
		$o->setConnection($mockConnection);

		$this->assertEquals($subjName . ' = ' . $value, $o->getDefinition($subjClause));
	}

	public function testFieldSubjectLikeValue() {
		$subjName = 'test';
		$subj = $this->getMockBuilder(FieldA::class)->disableOriginalConstructor()->getMock();
		$subj->method('getName')->willReturn($subjName);

		$value = uniqid('test:');
		$subjClause = new Clause($subj, 'like', $value);

		$mockDriver = $this->getMockBuilder(SqlGenericDriver::class)->getMock();

		$mockConnection = $this->getMockBuilder(ConnectionA::class)->getMock();
		$mockConnection->method('escape')->with($value)->willReturn($value);

		$o = new SqlClauseAccess();
		$o->setGrammarHelper($mockDriver);
		$o->setConnection($mockConnection);

		$this->assertEquals($subjName . ' LIKE %' . $value . '%', $o->getDefinition($subjClause));
	}
}
