<?php

namespace Core\Database;

/// <class name="DATATYPE" type="const">
///  Define all Data Types allowed 
/// </class>
class CType
{
    /*---------------------------------------------------------------------------------------------    
    | Numerics    
    ----------------------------------------------------------------------------------------------*/

    /// <propertie name="TINYINT" type="const">Storage 1 Byte</propertie>
    const TINYINT = 'TINYINT';

    /// <propertie name="SMALLINT" type="const">Storage 2 Bytes</propertie>              
    const SMALLINT = 'SMALLINT';

    /// <propertie name="INTEGER" type="const">Storage 4 Byte</propertie>  
    const INTEGER = 'INTEGER';

    /// <propertie name="BIGINT" type="const">Storage 8 Byte</propertie>
    const BIGINT = 'BIGINT';

    /// <propertie name="TINYINT" type="const">Storage 1 Byte</propertie>
    const FLOAT = 'FLOAT';

    /// <propertie name="TINYINT" type="const">Storage 1 Byte</propertie>
    const DOUBLE = 'DOUBLE';

    /// <propertie name="TINYINT" type="const">Storage 1 Byte</propertie>
    const DECIMAL = 'DECIMAL';

    /*---------------------------------------------------------------------------------------------    
    | Text    
    ----------------------------------------------------------------------------------------------*/

    /// <propertie name="TINYINT" type="const">Storage 1 Byte</propertie>
    const CHAR = 'CHAR';

    /// <propertie name="TINYINT" type="const">Storage 1 Byte</propertie>
    const VARCHAR = 'VARCHAR';

    /// <propertie name="TINYINT" type="const">Storage 1 Byte</propertie>
    const TEXT = 'TEXT';

    /*---------------------------------------------------------------------------------------------    
    | Object    
    ----------------------------------------------------------------------------------------------*/

    /// <propertie name="TINYINT" type="const">Storage 1 Byte</propertie>
    const DATETIME = 'DATETIME';

    /// <propertie name="TINYINT" type="const">Storage 1 Byte</propertie>
    const JSON = 'JSON';

}
