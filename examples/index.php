<?php

require __DIR__ . '/../vendor/autoload.php';
$forostranaSdk = new PetrovDAUtils\FotostranaSdk();
$forostranaSdk->getUser(111);