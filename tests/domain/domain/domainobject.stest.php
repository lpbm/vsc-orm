<?php
/**
 * @package domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 */
include_once ('fixtures/DummyTable.php');

class DomainObjectTest extends Snap_UnitTestCase {
	/**
	 * @var DomainObjectA
	 */
	private $state;

	public function setUp() {
		// begin transaction shit - if the case
		date_default_timezone_set('Europe/Bucharest');
		$this->state = new dummyTable();
	}

	public function tearDown () {
		unset ($this->state);
	}

	public function testInstantiation1 (){
		return $this->assertIsA($this->state, 'dummyTable');
	}

	public function testInstantiation2 (){
		return $this->assertIsA($this->state, 'DomainObjectA');
	}

	public function testFields () {
		foreach ($this->state->getFields() as $oColumn) {
			// this is broken: FIXME
			return $this->assertIsA ( $oColumn, 'FieldA');
		}
	}

	public function testPrimaryKey () {
		$this->state->setPrimaryKey($this->state->payload);
		return $this->assertIsA($this->state->getPrimaryKey(), 'KeyPrimary');
	}

	public function testGetterInteger () {
		$value = $this->state->getPayload ();
		return $this->assertEqual ($value->getValue(), 2);
	}

	public function testGetterNull () {
		$value = $this->state->getId();
		return $this->assertNull($value->getValue());
	}

	public function testSetterGetterInt () {
		$this->state= 1;
		$value = $this->state;

		return $this->assertEqual ($value, 1);
	}

	public function testSetterGetterString () {
		$var = 'asd';
		$this->state= $var;
		$value = $this->state;

		return $this->assertEqual ($value, $var);
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

		return $this->assertEqual($this->state->toArray(), $values);
	}

	public function testFromArray () {
		$values = array (
			'id' 		=> 1,
			'payload'	=> 'Ana are mere !! test" asd" ',
			'timestamp'	=> date('Y-m-d G:i:s'),
		);
		$this->state->fromArray ($values);

		return $this->assertEqual($this->state->toArray(), $values);
	}


	public function testGetFields () {
// 		d($this->state->getFields());
		return $this->todo ('get fields');
	}
	public function testGetFieldNames () {
		return $this->todo ('get field names');
	}
	public function testSetFieldsParent () {
		return $this->todo ('set fields parent');
	}
	public function testSetPrimaryKey () {
		return $this->todo ('set primary key');
	}
	public function testSetTableAlias () {
		return $this->todo ('set table alias');
	}
}

