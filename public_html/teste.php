<?php

require_once("../config.php");

use Core\Database\DbConnection;

$db = new DbConnection();
$db->SelectAll()->WhereProperty("Id").EqualTo(2);



