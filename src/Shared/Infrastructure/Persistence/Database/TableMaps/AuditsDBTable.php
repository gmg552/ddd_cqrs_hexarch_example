<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Persistence\Database\TableMaps;

use InvalidArgumentException;

class AuditsDBTable implements DBTable
{

	public function __construct(){

	}

		//Campos de la tabla
	public const ID = 'id';
	public const UUID = 'uuid';
	public const AUDIT_TYPE_ID = 'audit_type_id';
	public const OPERATOR_ID = 'operator_id';
	public const REAL_CHIEF_AUDITOR_ID = 'real_chief_auditor_id';
	public const STRAW_CHIEF_AUDITOR_ID = 'straw_chief_auditor_id';
	public const CONTROL_LEVEL_ID = 'control_level_id';
	public const BASE_SCHEME_ID = 'base_scheme_id';
	public const SCHEME_ORDER_ID = 'scheme_order_id';
	public const START_DATE = 'start_date';
	public const END_DATE = 'end_date';
	public const WORKDAYS = 'workdays';
	public const OFFSITE_WORKDAYS = 'offsite_workdays';
	public const INSIDE_WORKDAYS = 'inside_workdays';
	public const NUMBER = 'number';
	public const NOTES = 'notes';
	public const SIGNATURE_DATE = 'signature_date';
	public const DELETED_AT = 'deleted_at';
	public const CREATED_AT = 'created_at';
	public const UPDATED_AT = 'updated_at';


	//Relaciones de esta tabla con otras tablas
	private $tableRelationships = [
		[ ['audit_types'], ['audit_types.id'], ['audits.audit_type_id'] ],
		[ ['schemes'], ['schemes.id'], ['audits.base_scheme_id'] ],
		[ ['audit_control_level_types'], ['audit_control_level_types.id'], ['audits.control_level_id'] ],
		[ ['operators'], ['operators.id'], ['audits.operator_id'] ],
		[ ['auditors'], ['auditors.id'], ['audits.real_chief_auditor_id'] ],
		[ ['scheme_orders'], ['scheme_orders.id'], ['audits.scheme_order_id'] ],
		[ ['auditors'], ['auditors.id'], ['audits.straw_chief_auditor_id'] ],
	];

	private array $joins = [
		'audit_types' => [
			'audits.audit_type_id' => [
				['referencedTable' => 'audit_types',  'foreignKey' => 'audits.audit_type_id', 'localKey' => 'audit_types.id'],
			],
		],
		'schemes' => [
			'audits.base_scheme_id' => [
				['referencedTable' => 'schemes',  'foreignKey' => 'audits.base_scheme_id', 'localKey' => 'schemes.id'],
			],
			'schemes.parent_id' => [
				['referencedTable' => 'schemes',  'foreignKey' => 'audits.base_scheme_id', 'localKey' => 'schemes.id'],
				['referencedTable' => 'schemes',  'foreignKey' => 'schemes.parent_id', 'localKey' => 'schemes.id'],
			],
			'schemes.root_scheme_id' => [
				['referencedTable' => 'schemes',  'foreignKey' => 'audits.base_scheme_id', 'localKey' => 'schemes.id'],
				['referencedTable' => 'schemes',  'foreignKey' => 'schemes.root_scheme_id', 'localKey' => 'schemes.id'],
			],
			'schemes.scheme_file_id' => [
				['referencedTable' => 'schemes',  'foreignKey' => 'audits.base_scheme_id', 'localKey' => 'schemes.id'],
				['referencedTable' => 'schemes',  'foreignKey' => 'schemes.scheme_file_id', 'localKey' => 'schemes.id'],
			],
			'audit_control_level_types.scheme_id' => [
				['referencedTable' => 'audit_control_level_types',  'foreignKey' => 'audits.control_level_id', 'localKey' => 'audit_control_level_types.id'],
				['referencedTable' => 'schemes',  'foreignKey' => 'audit_control_level_types.scheme_id', 'localKey' => 'schemes.id'],
			],
			'scheme_orders.base_scheme_id' => [
				['referencedTable' => 'scheme_orders',  'foreignKey' => 'audits.scheme_order_id', 'localKey' => 'scheme_orders.id'],
				['referencedTable' => 'schemes',  'foreignKey' => 'scheme_orders.base_scheme_id', 'localKey' => 'schemes.id'],
			],
		],
		'services' => [
			'schemes.id' => [
				['referencedTable' => 'schemes',  'foreignKey' => 'audits.base_scheme_id', 'localKey' => 'schemes.id'],
				['referencedTable' => 'services',  'foreignKey' => 'schemes.id', 'localKey' => 'services.id'],
			],
		],
		'operator_cb_numeration_ranges' => [
			'schemes.operator_cb_numeration_range_id' => [
				['referencedTable' => 'schemes',  'foreignKey' => 'audits.base_scheme_id', 'localKey' => 'schemes.id'],
				['referencedTable' => 'operator_cb_numeration_ranges',  'foreignKey' => 'schemes.operator_cb_numeration_range_id', 'localKey' => 'operator_cb_numeration_ranges.id'],
			],
		],
		'audit_control_level_types' => [
			'audits.control_level_id' => [
				['referencedTable' => 'audit_control_level_types',  'foreignKey' => 'audits.control_level_id', 'localKey' => 'audit_control_level_types.id'],
			],
		],
		'operators' => [
			'audits.operator_id' => [
				['referencedTable' => 'operators',  'foreignKey' => 'audits.operator_id', 'localKey' => 'operators.id'],
			],
			'scheme_orders.holder_id' => [
				['referencedTable' => 'scheme_orders',  'foreignKey' => 'audits.scheme_order_id', 'localKey' => 'scheme_orders.id'],
				['referencedTable' => 'operators',  'foreignKey' => 'scheme_orders.holder_id', 'localKey' => 'operators.id'],
			],
		],
		'subjects' => [
			'operators.id' => [
				['referencedTable' => 'operators',  'foreignKey' => 'audits.operator_id', 'localKey' => 'operators.id'],
				['referencedTable' => 'subjects',  'foreignKey' => 'operators.id', 'localKey' => 'subjects.id'],
			],
			'subjects.legal_representative_id' => [
				['referencedTable' => 'operators',  'foreignKey' => 'audits.operator_id', 'localKey' => 'operators.id'],
				['referencedTable' => 'subjects',  'foreignKey' => 'operators.id', 'localKey' => 'subjects.id'],
				['referencedTable' => 'subjects',  'foreignKey' => 'subjects.legal_representative_id', 'localKey' => 'subjects.id'],
			],
			'operators.representative_id' => [
				['referencedTable' => 'operators',  'foreignKey' => 'audits.operator_id', 'localKey' => 'operators.id'],
				['referencedTable' => 'subjects',  'foreignKey' => 'operators.representative_id', 'localKey' => 'subjects.id'],
			],
			'auditors.id' => [
				['referencedTable' => 'auditors',  'foreignKey' => 'audits.real_chief_auditor_id', 'localKey' => 'auditors.id'],
				['referencedTable' => 'subjects',  'foreignKey' => 'auditors.id', 'localKey' => 'subjects.id'],
			],
		],
		'areas' => [
			'subjects.area_id' => [
				['referencedTable' => 'operators',  'foreignKey' => 'audits.operator_id', 'localKey' => 'operators.id'],
				['referencedTable' => 'subjects',  'foreignKey' => 'operators.id', 'localKey' => 'subjects.id'],
				['referencedTable' => 'areas',  'foreignKey' => 'subjects.area_id', 'localKey' => 'areas.id'],
			],
		],
		'cities' => [
			'subjects.city_id' => [
				['referencedTable' => 'operators',  'foreignKey' => 'audits.operator_id', 'localKey' => 'operators.id'],
				['referencedTable' => 'subjects',  'foreignKey' => 'operators.id', 'localKey' => 'subjects.id'],
				['referencedTable' => 'cities',  'foreignKey' => 'subjects.city_id', 'localKey' => 'cities.id'],
			],
		],
		'provinces' => [
			'cities.province_id' => [
				['referencedTable' => 'operators',  'foreignKey' => 'audits.operator_id', 'localKey' => 'operators.id'],
				['referencedTable' => 'subjects',  'foreignKey' => 'operators.id', 'localKey' => 'subjects.id'],
				['referencedTable' => 'cities',  'foreignKey' => 'subjects.city_id', 'localKey' => 'cities.id'],
				['referencedTable' => 'provinces',  'foreignKey' => 'cities.province_id', 'localKey' => 'provinces.id'],
			],
		],
		'countries' => [
			'provinces.country_id' => [
				['referencedTable' => 'operators',  'foreignKey' => 'audits.operator_id', 'localKey' => 'operators.id'],
				['referencedTable' => 'subjects',  'foreignKey' => 'operators.id', 'localKey' => 'subjects.id'],
				['referencedTable' => 'cities',  'foreignKey' => 'subjects.city_id', 'localKey' => 'cities.id'],
				['referencedTable' => 'provinces',  'foreignKey' => 'cities.province_id', 'localKey' => 'provinces.id'],
				['referencedTable' => 'countries',  'foreignKey' => 'provinces.country_id', 'localKey' => 'countries.id'],
			],
			'states.country_id' => [
				['referencedTable' => 'operators',  'foreignKey' => 'audits.operator_id', 'localKey' => 'operators.id'],
				['referencedTable' => 'subjects',  'foreignKey' => 'operators.id', 'localKey' => 'subjects.id'],
				['referencedTable' => 'cities',  'foreignKey' => 'subjects.city_id', 'localKey' => 'cities.id'],
				['referencedTable' => 'provinces',  'foreignKey' => 'cities.province_id', 'localKey' => 'provinces.id'],
				['referencedTable' => 'states',  'foreignKey' => 'provinces.state_id', 'localKey' => 'states.id'],
				['referencedTable' => 'countries',  'foreignKey' => 'states.country_id', 'localKey' => 'countries.id'],
			],
		],
		'states' => [
			'provinces.state_id' => [
				['referencedTable' => 'operators',  'foreignKey' => 'audits.operator_id', 'localKey' => 'operators.id'],
				['referencedTable' => 'subjects',  'foreignKey' => 'operators.id', 'localKey' => 'subjects.id'],
				['referencedTable' => 'cities',  'foreignKey' => 'subjects.city_id', 'localKey' => 'cities.id'],
				['referencedTable' => 'provinces',  'foreignKey' => 'cities.province_id', 'localKey' => 'provinces.id'],
				['referencedTable' => 'states',  'foreignKey' => 'provinces.state_id', 'localKey' => 'states.id'],
			],
		],
		'auditors' => [
			'audits.real_chief_auditor_id' => [
				['referencedTable' => 'auditors',  'foreignKey' => 'audits.real_chief_auditor_id', 'localKey' => 'auditors.id'],
			],
			'audits.straw_chief_auditor_id' => [
				['referencedTable' => 'auditors',  'foreignKey' => 'audits.straw_chief_auditor_id', 'localKey' => 'auditors.id'],
			],
		],
		'scheme_orders' => [
			'audits.scheme_order_id' => [
				['referencedTable' => 'scheme_orders',  'foreignKey' => 'audits.scheme_order_id', 'localKey' => 'scheme_orders.id'],
			],
		]
	];


	/**
	* Nombre de la tabla
	**/
	public function tableName() : string {
		return 'audits';
	}
	


	public function directRelationships() : array {
		return $this->tableRelationships;
	}

	public function joinsTo(string $targetTable, $throughKey = null, array $tableAlias = null) : array {
		$this->ensureHasValidParams($targetTable, $throughKey);

		if($throughKey)
			return $this->joins[$targetTable][$throughKey];

		$throughKeys = array_keys($this->joins[$targetTable]);
		$joins = $this->joins[$targetTable][$throughKeys[0]];
		$aliasedJoins = [];
		if($tableAlias !== null)
			foreach ($joins as $join) {
				array_push($aliasedJoins, [
					'referencedTable' => $tableAlias[$join['referencedTable']], 
					'localKey' => $tableAlias[$join['referencedTable']] . '.' . explode(".", $join['localKey'])[1],
					'foreignKey' => $join['foreignKey']
				]);
			}

		return $joins;
	}

	private function ensureHasValidParams(string $targetTable, $throughKey = null){

		if(!isset($this->joins[$targetTable]))
			throw new InvalidArgumentException("La tabla <$targetTable> no puede ser accedida desde <$this->tableName()>");

		if(!isset($this->joins[$targetTable][$throughKey]))
			throw new InvalidArgumentException("No se reconoce la clave <$throughKey> para acceder a la tabla <$targetTable>");

		$throughKeys = array_keys($this->joins[$targetTable]);
		if($throughKey === null && count($throughKeys) > 1)
			throw new InvalidArgumentException("$targetTable puede ser accedida de varias formas. Indique la clave por la cual acceder.");
	}

	/**
	 * Obtiene la clave local a partir de una clave externa
	 */
	public function localKeyOf(string $foreignKey) : string {
		return '';
	}


}