<?php

class DbTableColumnModel {
    public $Name;   
    public $FlagIsPk;
    public $FlagIsFk;
    public $ObjectType;
    public $OrdinalPosition;
    public $FlagIsNullable;
    public $FlagHasDefaultValue;
    public $CharMaxLength;
    public $Type; //Tipo => DbDataType
    public $Reference = []; //Tipo => dbFkModel
}