<?php

require_once("../config.php");

use \Core\Database\DbConnection;

$db = new DbConnection();
$db->SelectAll()->WhereProperty("Idata")
				->BiggerAndEqualTo(25)
				->Execute();