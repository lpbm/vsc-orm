<?php
/**
 * @package vsc_domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.05.29
 */
abstract class vscSqlIndexAccessA extends vscAccessEntityA {
	abstract public function getType(vscIndexA $oIndex);
}
