<?php
/**
 * @package domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 */

namespace domain\domain\fields;

use orm\domain\domain\fields\FieldInteger;

class FieldIntegerTest extends \PHPUnit_Framework_TestCase {
	private $state;

	public function setUp () {
		$this->state = new FieldInteger('integerField');
	}

	public function tearDown () {
		unset ($this->state);
	}

	public function testInstantiation () {
		return $this->assertInstanceOf(FieldInteger::class, $this->state);
	}

	public function testInstantiationName () {
		return $this->assertEquals ('integerField', $this->state->getName());
	}
}
