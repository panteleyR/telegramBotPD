<?php

define("BOT_KEY", "594499520:AAHILjhO0K-w-sMVPb0oVEQNq6VheC5V1k4");

function connectBD(){
    $hostname='localhost';
    $username="u0512445_default";
    $password="_HV9JgZ7";
    $dbname="u0512445_default";
    $charset = 'utf8';

    $dsn = "mysql:host=$hostname;dbname=$dbname;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT => true,
    ];
    $pdo = new PDO($dsn, $username, $password, $opt);
    return $pdo;
}
function parsJson($json){
    return json_decode($json, true);
}
function sendPic($idChat){
    print_r('assssssssssssssssssssssssssssssssssss'."\n \n \n");
    $key='553147137:AAGaiG9fkw9RMkXUEep55rC-xqIJDJBJmlQ';
    $url="https://mospolybot.ru/giff.gif";
    $myCurl = curl_init();
    curl_setopt_array($myCurl, array(
        CURLOPT_URL => 'https://api.telegram.org/bot'.BOT_KEY.'/sendVideo',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,

        CURLOPT_POSTFIELDS => http_build_query(array(
            'chat_id'=> $idChat,
            'video' => $url
        ))
    ));
    $response=curl_exec($myCurl);
    curl_close($myCurl);
}

function sendMessage2($idChat, $text,$mess){
    $myCurl = curl_init();
    $key='553147137:AAGaiG9fkw9RMkXUEep55rC-xqIJDJBJmlQ';
    curl_setopt_array($myCurl, array(
        CURLOPT_URL => 'https://api.telegram.org/bot'.BOT_KEY.'/sendMessage',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(array(
            'chat_id'=> $idChat,
            'ReplyKeyboardMarkup' => $mess,
            'text' => $text
        ))
    ));
    curl_exec($myCurl);
    curl_close($myCurl);

}

function sendMessage($idChat, $text){
    $myCurl = curl_init();
    $key='553147137:AAGaiG9fkw9RMkXUEep55rC-xqIJDJBJmlQ';
    curl_setopt_array($myCurl, array(
        CURLOPT_URL => 'https://api.telegram.org/bot'.BOT_KEY.'/sendMessage',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(array(
            'chat_id'=> $idChat,
            'text' => $text
        ))
    ));
    curl_exec($myCurl);
    curl_close($myCurl);

}

function takeInfo($susuke){
    return array(
        'chatId'=> $susuke['message']['chat']['id'],
        'name' => $susuke['message']['chat']['username'],
        'text'=> $susuke['message']['text']
    )
        ;
}

function getCommand($slova){
    $str=[];
    $arr = preg_split("/( )+/", $slova);
    foreach ($arr as $word) {
        $str[]=$word;
    }
    return $str;
}



function checkRole($user){
    $DB=connectBD();
    foreach ($DB->query("SELECT typeRole FROM telegramBot WHERE username = '".$user['message']['from']['username']."'") as $row){

        $role=$row['typeRole'];

    }
    /*$role = $DB->query('SELECT typeRole FROM telegramBot WHERE first_name = '.$user['message']['form']['first_name']);*/
    $DB=NUll;
    return $role;
}




