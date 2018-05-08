<?php
namespace domain\connections\PDO;


use orm\domain\connections\PDOConnection;

class connectTest extends \PHPUnit_Framework_TestCase
{
	public function testBasicConnect() {
		$o = new PDOConnection();

	}
}
