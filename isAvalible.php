<?php
$resp = array('ok'=>true,'errors' =>[array('inf'=>'blocked', 'isGroup'=>true, 'lockedBy'=>['abc','xyz'], 'name'=>'')]);
echo json_encode($resp);