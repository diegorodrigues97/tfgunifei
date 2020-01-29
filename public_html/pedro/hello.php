<?php

$db = new DbConnection();
$db->SelectAll()->WhereProperty("Idata")
				->BiggerAndEqualTo(25)
				->Execute();