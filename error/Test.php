<?php

require_once '../align/AlignError.php';

$testerror = AlignError::getInstance();
$testerror->set_error('GAP');
$testerror->unset_error('GAP');
$res = $testerror->print_error('GAP');
echo $res;