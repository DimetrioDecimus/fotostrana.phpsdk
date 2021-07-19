<?php

require __DIR__ . '/../vendor/autoload.php';

use PetrovDAUtils;

$forostranaSdk = new PetrovDAUtils\FotostranaSdk();
$forostranaSdk->getUser(111);