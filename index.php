<?php

require 'vendor/autoload.php';
$update = json_decode(file_get_contents('php://input'));

if (isset($update)) {
    require 'tgBot.php';
    return;
}
require 'view/dashboard.php';




