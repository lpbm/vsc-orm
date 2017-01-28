<?php
namespace mocks\domain\connections;


use orm\domain\connections\NullSql;

class DummyConnection extends NullSql {
	public function __construct() {}
} 