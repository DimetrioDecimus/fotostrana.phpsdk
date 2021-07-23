<?php

require __DIR__ . '/../vendor/autoload.php';
$forostranaSdk = new PetrovDAUtils\FotostranaSdk();
if (!$forostranaSdk->error()) {
    $user = $forostranaSdk->getServiceUser()->getUserProfile(111);
}

