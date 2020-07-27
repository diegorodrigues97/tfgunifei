<?php

include './connection.php';
include './dbModel.php';
include './dbTableModel.php';
include './dbTableColumnModel.php';
include './dbFkModel.php';
include './DbDataType.php';


class DbControl
{
    private $conn;
    private $database;

    function __construct()
    {
        $this->database = new DbModel();
        $this->conn = getConnection();
    }

    function main()
    {
        $this->extractDbInfo();
        $this->extractTable();
        return $this->database;
    }

    public function extractDbInfo()
    {
        $pgsql = "SELECT catalog_name
        FROM information_schema.information_schema_catalog_name";
        $stmt = $this->conn->prepare($pgsql);
        $stmt->execute();
        $dbResult = $stmt->fetchAll();
        $this->database->Name = $dbResult[0][0];

        $pgsql = "SELECT form_of_use
        FROM information_schema.character_sets";
        $stmt = $this->conn->prepare($pgsql);
        $stmt->execute();
        $dbResult = $stmt->fetchAll();
        $this->database->Charset = $dbResult[0][0];
    }

    public function extractTable()
    {
        $pgsql = "SELECT table_name 
        FROM information_schema.tables
        WHERE table_schema='public'
        AND table_type='BASE TABLE'";
        $stmt = $this->conn->prepare($pgsql);
        $stmt->execute();
        $tbResult = $stmt->fetchAll();

        for ($i = 0; $i < count($tbResult); $i++) {
            $table = new DbTableModel();
            $table->Name = $tbResult[$i][0];
            $this->extractColumn($table);
            array_push($this->database->Tables, $table);
        }
    }

    public function extractColumn(DbTableModel $table)
    {
        $pgsql = "SELECT  column_name, ordinal_position, is_nullable,  column_default, character_maximum_length, data_type 
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = '$table->Name'";
        $stmt = $this->conn->prepare($pgsql);
        $stmt->execute();
        $cl = $stmt->fetchAll();


        $pgsql = "SELECT
            kcu.column_name, 
            ccu.table_name AS foreign_table_name,
            ccu.column_name AS foreign_column_name
            FROM information_schema.table_constraints AS tc 
            JOIN information_schema.key_column_usage AS kcu
            ON tc.constraint_name = kcu.constraint_name
            AND tc.table_schema = kcu.table_schema
            JOIN information_schema.constraint_column_usage AS ccu
            ON ccu.constraint_name = tc.constraint_name
            AND ccu.table_schema = tc.table_schema
            WHERE tc.constraint_type = 'FOREIGN KEY' AND tc.table_name='$table->Name'";
        $stmt = $this->conn->prepare($pgsql);
        $stmt->execute();
        $fk = $stmt->fetchAll();

        $pgsql = "SELECT
            kcu.column_name, 
            ccu.table_name 
            AS foreign_table_name
            FROM information_schema.table_constraints AS tc 
            JOIN information_schema.key_column_usage AS kcu
            ON tc.constraint_name = kcu.constraint_name
            AND tc.table_schema = kcu.table_schema
            JOIN information_schema.constraint_column_usage AS ccu
            ON ccu.constraint_name = tc.constraint_name
            AND ccu.table_schema = tc.table_schema
            WHERE tc.constraint_type = 'PRIMARY KEY' AND tc.table_name='$table->Name'";
        $stmt = $this->conn->prepare($pgsql);
        $stmt->execute();
        $pk = $stmt->fetchAll();
        

        for ($j = 0; $j < count($cl); $j++) {
            $column = new DbTableColumnModel();
            $column->Name = $cl[$j]['column_name'];
            $column->OrdinalPosition = $cl[$j]['ordinal_position'];
            $column->CharMaxLength = $cl[$j]['character_maximum_length'];
            //$column->Type = $cl[$j]['data_type'];
            if($cl[$j]['is_nullable'] == 'YES') {
                $column->FlagIsNullable = true;
            }
            if($cl[$j]['column_default'] != null) {
                $column->FlagHasDefaultValue = true;
            } 
            if($column->Name == $pk[0][0]){
                $column->FlagIsPk = true;
            }
            if(!empty($fk) && $column->Name == $fk[0][0]){
                $column->FlagIsFk = true;
                $column->ObjectType = $fk[0]['foreign_table_name'];
                $fkModel = new DbFkModel();
                $fkModel->TargetTable = $fk[0]['foreign_table_name'];
                $fkModel->TargetColumn = $fk[0]['foreign_column_name'];
                array_push($column->Reference, $fkModel);
            }

            switch ($cl[$j]['data_type']) {
                case 'integer':                    
                    $column->Type = 'Int';
                    break;
                case 'double precision':                    
                    $column->Type = 'Double';
                    break;
                case 'numeric':                   
                    $column->Type = 'Float';
                    break;
                case 'character varying': 
                    $column->Type = 'VarChar';
                    break;
                case 'date': 
                    $column->Type = 'Date';
                    break;
                case 'time': 
                    $column->Type = 'Time';
                    break;
                case 'text': 
                    $column->Type = 'Text';
                    break;
                case 'timestamp': 
                    $column->Type = 'DateTime';
                    break;
            }

            array_push($table->Columns, $column);
        }
    }
}


