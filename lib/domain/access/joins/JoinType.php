<?php
/**
 * @pacakge \orm\domain\access\joins
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
namespace orm\domain\access\joins;

interface JoinType {
	const INNER = 0;
	const OUTER = 1;
	const LEFT	= 3; // LEFT | OUTER = true
	const RIGHT	= 5; // RIGHT | OUTER = true
}
