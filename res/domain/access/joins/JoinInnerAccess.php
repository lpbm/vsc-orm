<?php
/**
 * @pacakge domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
class JoinInnerAccess  extends SqlJoinAccessA {
	public function getType () {
		return 'INNER';
	}
}
