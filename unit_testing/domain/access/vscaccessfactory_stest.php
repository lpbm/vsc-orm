<?php
/* Db constants
 -----------------------*/
import ('models/vsc');
import ('models/connections');
import ('exceptions');

// include_once ('fixtures/dummytable.class.php'); // the definition of the entity
include_once ('../domain/fixtures/dataobject.class.php'); // the definition of the data object

class vscAccessFactoryTest extends Snap_UnitTestCase {
	private $connection;

	public function setUp () {
		$this->connection = new vscTdo();
		$this->connection->getConnection()->selectDatabase('test');
	}

	public function tearDown() {}
}
