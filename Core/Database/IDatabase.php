<?php

namespace Core\Database;

interface IRepositoryGeneral 
{ 
    public function GetById($id);

    public function GetAll();

    public function WhereProperty($value);

    public function Equal($value);

    public function Like($value);

    public function BiggerTo();

    public function BiggerAndEqualTo();

    public function LessTo(); 

    public function LessAndEqualTo();
}