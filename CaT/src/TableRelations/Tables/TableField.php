<?php

namespace CaT\TableRelations\Tables;

use CaT\Filter as Filters;
/**
 * A field combining Predicate-Fields and table functinality.
 * Note: it may still be used in Predicates.
 */
class TableField extends Filters\Predicates\Field implements abstractTableField {

	public function __construct(Filters\PredicateFactory $f, $name, $table_id = null) {
		$this->table_id = $table_id;
		parent::__construct($f, $name);
	}

	/**
	 * Any TableField may be related to a Table.
	 * Two different Tables may contain fields with equal name.
	 *
	 * @return	string
	 */
	public function tableId() {
		return $this->table_id;
	}

	/**
	 * To avoid ambiguity we have to include related table-id into fieldname.
	 *
	 * @return	string
	 */
	public function name() {
		return $this->table_id.'.'.parent::name();
	}
}