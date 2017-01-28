<?php
/**
 * @package domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 */
namespace domain\domain;

use mocks\domain\domain\DummyTable;
use orm\domain\domain\DomainObjectA;
use orm\domain\domain\fields\FieldA;
use orm\domain\domain\indexes\KeyPrimary;

class DomainObjectTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DomainObjectA
	 */
	private $state;

	public function setUp() {
		// begin transaction shit - if the case
		date_default_timezone_set('Europe/Bucharest');
		$this->state = new DummyTable();
	}

	public function tearDown () {
		unset ($this->state);
	}

	public function testInstantiation1 (){
		return $this->assertInstanceOf(DummyTable::class, $this->state);
	}

	public function testInstantiation2 (){
		return $this->assertInstanceOf(DomainObjectA::class, $this->state);
	}

	public function testFields () {
		foreach ($this->state->getFields() as $oColumn) {
			// this is broken: FIXME
			return $this->assertInstanceOf (FieldA::class, $oColumn);
		}
	}

	public function testPrimaryKey () {
		$this->state->setPrimaryKey($this->state->payload);
		return $this->assertInstanceOf (KeyPrimary::class, $this->state->getPrimaryKey());
	}

	public function testGetterInteger () {
		$value = $this->state->getPayload ();
		return $this->assertEquals ($value->getValue(), 2);
	}

	public function testGetterNull () {
		$value = $this->state->getId();
		return $this->assertNull($value->getValue());
	}

	public function testSetterGetterInt () {
		$this->state= 1;
		$value = $this->state;

		return $this->assertEquals ($value, 1);
	}

	public function testSetterGetterString () {
		$var = 'asd';
		$this->state= $var;
		$value = $this->state;

		return $this->assertEquals ($value, $var);
	}

	public function testSetterGetterNull () {
		$this->state =  null;
		$value = $this->state;

		return $this->assertNull ($value);
	}

	public function testToArray () {
		$values = array (
			'id' 		=> 1,
			'payload'	=> 'Ana are mere !! test" asd" ',
			'timestamp'	=> date('Y-m-d G:i:s'),
		);

		$this->state->id->setValue($values['id']);
		$this->state->payload->setValue($values['payload']);
		$this->state->timestamp->setValue($values['timestamp']);

		return $this->assertEquals($this->state->toArray(), $values);
	}

	public function testFromArray () {
		$values = array (
			'id' 		=> 1,
			'payload'	=> 'Ana are mere !! test" asd" ',
			'timestamp'	=> date('Y-m-d G:i:s'),
		);
		$this->state->fromArray ($values);

		return $this->assertEquals($this->state->toArray(), $values);
	}


	public function testGetFields () {
		return $this->markTestIncomplete ('get fields');
	}
	public function testGetFieldNames () {
		return $this->markTestIncomplete ('get field names');
	}
	public function testSetFieldsParent () {
		return $this->markTestIncomplete ('set fields parent');
	}
	public function testSetPrimaryKey () {
		return $this->markTestIncomplete ('set primary key');
	}
	public function testSetTableAlias () {
		return $this->markTestIncomplete ('set table alias');
	}
}

