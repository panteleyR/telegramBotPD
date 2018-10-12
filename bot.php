<?php

define("BOT_KEY", "594499520:AAHILjhO0K-w-sMVPb0oVEQNq6VheC5V1k4");
include_once 'functions.php';
include_once 'commands.php';
include_once 'switchComand.php';


$data = json_decode( file_get_contents('php://input'), true);
$myCurl = curl_init();

if(!empty($data)){
        $message = $data;
        switchCommand($message);
}

