<?php

namespace Qalis\Shared\Infrastructure\Legacy\SQLMDDataTableHelpers;

use Illuminate\Support\Facades\DB;
use PDO;

class SQLMDDataTableHelper {


    public function fillMasterDetailSqlDataTable($sqlMDDataTable, $parentRow = null) {

        $detailRows = [];
        foreach ($sqlMDDataTable->rows as $key => $row) {
            if (($parentRow) && ($parentRow->{$sqlMDDataTable->masterTable->local_key_column} == $row->{$sqlMDDataTable->masterTable->foreign_key_column})) {
                array_push($detailRows, $row);
            }
            if ($sqlMDDataTable->detailTable != null) {
                $this->fillMasterDetailSqlDataTable($sqlMDDataTable->detailTable, $row);
            }
        }
		if ($parentRow) 
			$parentRow->detailRows = (object)$detailRows;

        return true;
    }

    public function filterSqlDataTable($sqlMDDataTable) {

        $rows = [];
        foreach($sqlMDDataTable->sqlQuery->result as $row) {
            if ($this->evalFilter($row, $sqlMDDataTable->filter)) {
                $row->sqlMDDataTable = $sqlMDDataTable;
                array_push($rows, $row);
            }
            if ($sqlMDDataTable->detailTable != null) {
                $this->filterSqlDataTable($sqlMDDataTable->detailTable);
            }
        }
        $sqlMDDataTable->rows = (object)$rows;

    }

    private function replaceParamsInQuery($params, $query){
        foreach ($params as $key => $value) {
            $query = preg_replace("/{{\s*".$key."\s*}}/i", $value, $query);
        }
        return $query;
    }

    public function executeQuery($sqlMDDataTable, $params = []) {
        if (!$sqlMDDataTable->sqlQuery->result) {
          
            $sqlMDDataTable->sqlQuery->query = $this->replaceParamsInQuery($params, $sqlMDDataTable->sqlQuery->query);
            
            if ($sqlMDDataTable->sqlQuery->connection) {
				$source = json_decode($sqlMDDataTable->sqlQuery->connection);
				//Debe modificarse para que valga para cualquier gestor de bases de datos: sql server, etc
                config(['database.connections.mysql_source.host' => $source->db_host]);
                config(['database.connections.mysql_source.database' => $source->db_database]);
                config(['database.connections.mysql_source.username' => $source->db_user]);
				config(['database.connections.mysql_source.password' => $source->db_pass]);
				if($sqlMDDataTable->sqlQuery->dynamic_query){
					$statements = $this->getMultistatements($sqlMDDataTable->sqlQuery->query);
                    $sqlQuery = $this->executeMultistatement($statements, 'mysql_source');			
					$sqlMDDataTable->sqlQuery->result = $this->executeMultistatement($statements, 'mysql_source');					
				}					
				else
                	$sqlMDDataTable->sqlQuery->result = DB::connection('mysql_source')->select($sqlMDDataTable->sqlQuery->query); //ejecuta un código sql contra la base de datos establecida en sqlQuery.connection o en la bd por defecto
                DB::disconnect('mysql_source');
            } else {
				if($sqlMDDataTable->sqlQuery->dynamic_query){ //devuelve un string con una consulta formada de forma dinamica
					$statements = $this->getMultistatements($sqlMDDataTable->sqlQuery->query);	
                    $sqlQuery = $this->executeMultistatement($statements, null);				
					$sqlMDDataTable->sqlQuery->result = $sqlQuery != null? DB::select($sqlQuery) : [];
				}										
				else
                	$sqlMDDataTable->sqlQuery->result = DB::select($sqlMDDataTable->sqlQuery->query); //ejecuta un código sql contra la base de datos establecida en sqlQuery.connection o en la bd por defecto
            }
            if ($sqlMDDataTable->detailTable) {
                $this->executeQuery($sqlMDDataTable->detailTable, $params);
            }

        }

	}

	private function executeMultistatement($statements = [], $conn = null){				
		if($conn)
			$pdo = DB::getPdo()->connection($conn); 
		else
			$pdo = DB::getPdo();		

		foreach($statements as $st){
			$st = $pdo->prepare($st);
			$st->execute();
			$result = $st->fetchAll(PDO::FETCH_ASSOC);//hacer llamada a fetchAll para que no dé "general error: 2014 Cannot execute queries while other unbuffered queries are active."
			$st->closeCursor();
		}
		return $result[0]['@sql']; //asumimos que el string con la consulta dinamica se encuentra en una variable llamada @sql
	}
	
	private function getMultistatements($query){
		$statements = str_getcsv($query, ";", "'");
		$results = [];
		foreach($statements as $value)
			if(strlen(trim($value)) > 0)
				array_push($results, trim($value));
		return $results;
	}

    public function evalFilter($row, $filter) {

        if (!$filter) return true;

        $evalExpresion = "";
        $filter = json_decode($filter);
        $row = (array)$row;

        foreach($filter as $key => $item) {

            $item = (array)$item;
            $key = key($item);


            if ($key == 'string_comp') {

                $item[$key] = (array)$item[$key];

                switch ($item[$key]['operator']) {
                    case 'like':
                        $evalExpresion = $evalExpresion . ((strpos($row[$item[$key]['col_name']], $item[$key]['string_value']) !== false) ? "true" : "false");
                        break;

                    case 'not_like':
                        $evalExpresion = $evalExpresion . ((strpos($row[$item[$key]['col_name']], $item[$key]['string_value']) === false) ? "true" : "false");
                        break;

                    case '=':
                        $evalExpresion = $evalExpresion . ($row[$item[$key]['col_name']] == $item[$key]['string_value'] ? 'true' : 'false');
                        break;

                    case '!=':
                        $evalExpresion = $evalExpresion . ($row[$item[$key]['col_name']] != $item[$key]['string_value'] ? 'true' : 'false');
                        break;

                }


            } elseif ($key == 'numeric_comp') {

                $item[$key] = (array)$item[$key];


                switch ($item[$key]['operator']) {
                    case '<':
                        $evalExpresion = $evalExpresion . ($row[$item[$key]['col_name']] < $item[$key]['value'] ? 'true' : 'false');;
                        break;

                    case '>':
                        $evalExpresion = $evalExpresion . ($row[$item[$key]['col_name']] > $item[$key]['value'] ? 'true' : 'false');
                        break;

                    case '<=':
                        $evalExpresion = $evalExpresion . ($row[$item[$key]['col_name']] <= $item[$key]['value'] ? 'true' : 'false');
                        break;

                    case '>=':
                        $evalExpresion = $evalExpresion . ($row[$item[$key]['col_name']] >= $item[$key]['value'] ? 'true' : 'false');
                        break;

                    case '=':
                        $evalExpresion = $evalExpresion . ($row[$item[$key]['col_name']] === $item[$key]['value'] ? 'true' : 'false');
                        break;

                    case '!=':
                        $evalExpresion = $evalExpresion . ($row[$item[$key]['col_name']] !== $item[$key]['value'] ? 'true' : 'false');
                        break;

                }

            } else {
                $evalExpresion = $evalExpresion . $item[$key];
            }

        }

        $evalExpresion = 'return '.$evalExpresion.';';

        return eval($evalExpresion);
    }

    public function execute($sqlMDDataTable, $params = []) {
        $this->executeQuery($sqlMDDataTable, $params);
        $this->filterSqlDataTable($sqlMDDataTable);
        $this->fillMasterDetailSqlDataTable($sqlMDDataTable, null);
    }

}
