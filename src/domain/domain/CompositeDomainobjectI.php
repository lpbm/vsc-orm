<?php
/**
 * @pacakge domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.05.20
 */
namespace orm\domain\domain;

interface CompositeDomainObjectI extends DomainObjectI {
	/**
	 * Gets the component domain objects
	 */
	public function getDomainObjects ();

	/**
	 * Gets the foreign key relations between the components
	 * Alias for self::getDomainObjectRelations
	 */
	public function getForeignKeys ();

}
