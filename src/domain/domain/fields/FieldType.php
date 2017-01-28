<?php
namespace orm\domain\domain\fields;

interface FieldType {
	const INTEGER	= 0;
	const DECIMAL	= 1;
	const TEXT		= 2;
	const ENUM 		= 3;
	const DATETIME	= 4;
}
