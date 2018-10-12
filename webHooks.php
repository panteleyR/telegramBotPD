<?php

define("BOT_KEY", "594499520:AAHILjhO0K-w-sMVPb0oVEQNq6VheC5V1k4");
$url="https://mospolybot.ru/testWebHooks/bot.php";
$myCurl = curl_init();
curl_setopt_array($myCurl, array(
    CURLOPT_URL => 'https://api.telegram.org/bot'.BOT_KEY.'/setWebhook',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,

    CURLOPT_POSTFIELDS => http_build_query(array(

       'url' => $url
    ))
));
$response=curl_exec($myCurl);
print_r("\n \n RESPNOSEEED2!!!!!!!!!!!! \n".$response);
print_r( gettype( $response));
curl_close($myCurl);


$myCurl = curl_init();
curl_setopt_array($myCurl, array(
    CURLOPT_URL => 'https://api.telegram.org/bot'.BOT_KEY.'/getMe',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,

    CURLOPT_POSTFIELDS => http_build_query(array(
/*
        'url' => $url*/
    ))
));
$response=curl_exec($myCurl);
print_r("\n \n RESPNOSEEED2!!!!!!!!!!!! \n".$response);
print_r( gettype( $response));
curl_close($myCurl);