<?php

namespace Core\Database;

use Core\Database\Data\Schema;

interface IDriver
{

    public function SetConnection(string $name, string $host, string $user, string $password);

    public function GetDatabaseSchema() : Schema;

    public function CreateOrUpdateDatabase(Schema $schema) : string;

    public function Pull(object $entity, $commandList = []);

    public function Delete(object $value);

    public function Insert(object $value);

    public function Update(object $value);

    public function GetDatabaseName() : string;
}