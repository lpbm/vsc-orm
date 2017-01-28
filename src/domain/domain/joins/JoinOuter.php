<?php
namespace orm\domain\domain\joins;

use orm\domain\access\joins\JoinType;

class JoinOuter extends JoinA {
	private $iType = JoinType::INNER;
}
