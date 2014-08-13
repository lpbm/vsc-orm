<?php
/**
 * @pacakge \orm\domain\connections
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
namespace orm\domain\connections;

interface ConnectionType {
	const nullsql		= -1;
	const mysql			= 1;
	const postgresql	= 2;
	const sqlite		= 3;
	const mssql			= 4;
}
