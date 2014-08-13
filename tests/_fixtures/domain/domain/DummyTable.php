<?php
namespace \_fixtures\domain\domain;

use \orm\domain\domain;

class DummyTable extends DomainObjectA {
	protected $name = 'dummy';
	public $id;
	public $payload;
	public $timestamp;

	public function buildObject() {
		$this->id 		= new FieldInteger('id');
		$this->id->setAutoIncrement (true);

		$this->payload 		= new FieldText('payload');
		$this->timestamp 	= new FieldDateTime ('ts');

		// this is used later in the testGetter - if you modify here, modify the Entity.ptest.php file
		$this->setPayload(2);

		$this->setPrimaryKey ($this->id);
	}
}
