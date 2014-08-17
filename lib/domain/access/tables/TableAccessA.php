<?php
namespace orm\domain\access\tables;

use orm\domain\access\AccessA;
use orm\domain\domain\DomainObjectI;

abstract class TableAccessA extends AccessA {
	//
	public function outputForSelect ( DomainObjectI $oDomainObject) {}
}
