<?php
/**
 * @pacakge domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
namespace orm\domain\access\joins;

class JoinOuterAccess extends SqlJoinAccessA {
	public function getType () {
		return 'LEFT';
	}
}
