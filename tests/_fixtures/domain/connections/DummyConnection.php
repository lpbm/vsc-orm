<?php
/**
 * Created by PhpStorm.
 * User: habarnam
 * Date: 8/13/14
 * Time: 3:37 PM
 */

namespace _fixtures\domain\connections;


use orm\domain\connections\NullSql;

class DummyConnection extends NullSql {
	public function __construct() {}
} 