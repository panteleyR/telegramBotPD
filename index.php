<?php

define("BOT_KEY", "594499520:AAHILjhO0K-w-sMVPb0oVEQNq6VheC5V1k4");

$groupUs="171-361";

$myCurl = curl_init();

curl_setopt_array($myCurl, array(
    CURLOPT_URL => 'http://rasp.dmami.ru/site/group?group='.$groupUs./*173-422*/'&session=0',
    CURLOPT_HEADER => false,
    /*CURLOPT_FOLLOWLOCATION => true,*/
    CURLOPT_RETURNTRANSFER => true,

    CURLOPT_POST => true,
    CURLOPT_REFERER => 'http://rasp.dmami.ru/',
    CURLOPT_POSTFIELDS => array(/*
        'group' => '173-422',
        'session' => 0
*/
    )
));
$resposnse=curl_exec($myCurl);
$resposnse=json_decode($resposnse,true);
print_r($resposnse["grid"]);