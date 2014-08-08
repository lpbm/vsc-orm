<?php
/**
 * @pacakge domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
class JoinOuterAccess extends SqlJoinAccessA {
	public function getType () {
		return 'LEFT';
	}
}
