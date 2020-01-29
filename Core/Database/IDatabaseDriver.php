<?php

namespace Core\Database;

interface IDatabaseDriver
{
    function GetQuery($commandList = [], $entityName);

}