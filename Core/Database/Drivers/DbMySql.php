<?php

namespace Core\Database;

use Core\Database\IDatabase; 

class DbMySql implements IDatabaseDriver
{
    private $Query;
    private $EntityName;
    private $CommandList;
    private $Vars = [];

    function GetQuery($commandList = [], $entityName)
    {
        $this->EntityName = $entityName;
        $this->CommandList = $commandList;

        $this->Query = "";

        return $this->BuildQuery();
    }

    private function BuildQuery()
    {
        //Step through all commands
        foreach($this->CommandList as $command)
        {
            try
            {
                if(!$command->Parameter)
                {
                    call_user_func($command->Name);
                }
                else
                {
                    call_user_func($command->Name, $command->Parameters);
                }
            }
            catch(Exception $ex)
            {
                throw new Exception("Command not found: $command->Name");
            }
        }

        return $this->Query;
    }

    public function SelectAll()
    {
        $this->Query += " select * from $this->EntityName ";
    }

    public function WhereProperty($parameter)
    {
        $this->Query += " where $parameter ";
    }

    public function EqualTo($parameter)
    {
        $varName = $this->GenerateVarName();
        $this->Query += " = :$varName ";
        $this->Vars[$varName] = $parameter;
    }

    public function GetById($id)
    {
        $this->query = "select * " . $EntityName . " where ";
    }

    private function GenerateVarName()
    {
        return "var" + $this->Vars->count();
    }
}