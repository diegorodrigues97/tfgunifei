<?php

namespace Core\Database;

use Core\Database\Data\Schema;

interface IRepository
{ 
    /*-------------------------------------------------------------------------------- 
	|	DEFINITIONS
    ----------------------------------------------------------------------------------*/

    function SetEntity(object $object) : void;

    /*-------------------------------------------------------------------------------- 
	|	STATEMENTS
    ----------------------------------------------------------------------------------*/

    function Where($value) : IRepository;

    function And($value) : IRepository;

    function Or($value) : IRepository;

    function Equal($value) : IRepository;

    function Like($value) : IRepository;

    function BiggerTo($value) : IRepository;

    function BiggerAndEqualTo($value) : IRepository;

    function LessTo($value) : IRepository; 

    function LessAndEqualTo($value) : IRepository;
        
    function Page(int $itemsPerPage, int $currentPage) : IRepository;
    

    /*-------------------------------------------------------------------------------- 
	|	ACTIONS
    ----------------------------------------------------------------------------------*/

    function Insert($targetClasses);

    function Update($targetClasses);

    function Delete($targetClasses);

    
    /*-------------------------------------------------------------------------------- 
	|	DATABASE MANAGEMENT
    ----------------------------------------------------------------------------------*/

    function UpdateDatabaseSchema(Schema $schema) : string;
}