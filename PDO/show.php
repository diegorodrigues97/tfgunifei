<?php

include './dbControl.php';

$control = new DbControl();

echo json_encode($control->main());