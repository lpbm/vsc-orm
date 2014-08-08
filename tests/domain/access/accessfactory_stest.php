<?php
/* Db constants
 -----------------------*/




// include_once ('fixtures/DummyTable.php'); // the definition of the entity
include_once ('../domain/fixtures/DataObject.php'); // the definition of the data object

class AccessFactoryTest extends Snap_UnitTestCase {
	private $connection;

	public function setUp () {
		$this->connection = new Tdo();
		$this->connection->getConnection()->selectDatabase('test');
	}

	public function tearDown() {}
}
