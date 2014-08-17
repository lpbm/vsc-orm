<?php
/**
 * @package domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 */


class FieldIntegerTest extends Snap_UnitTestCase {
	private $state;

	public function setUp () {
		$this->state = new FieldInteger ('integerField');
	}

	public function tearDown () {
		unset ($this->state);
	}

	public function testInstantiation () {
		return $this->assertIsA ($this->state, 'vscFieldInteger');
	}

	public function testInstantiationName () {
		return $this->assertEqual (
			$this->state->getName(),
			'integerField'
		);
	}
}
