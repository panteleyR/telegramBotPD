<?php

function filtrWords($mess,$user){
    $mess2=array(
        1 => 'хуй',
        2 => 'блять',
        3 => 'сука',
        4 => 'мразь',
        5 => 'соси',
        6 => 'нахуй',
        7 => 'пизду',
        8 => 'ненавижу',
        9 => 'уебок',
        10 => 'Хуй',
        11 => 'Блять',
        12 => 'Сука',
        13 => 'Мразь',
        14 => 'Соси',
        15 => 'Нахуй',
        16 => 'Пизду',
        17 => 'Ненавижу',
        18 => 'Уебок',

    );

    $z=false;
    for($i=0;$i<count($mess);$i++){
        print_r($mess[$i]."\n");
        for($j=0;$j<count($mess2);$j++) {
            print_r("\n".ucfirst($mess2[$j]));
            if ($mess[$i] == $mess2[$j] or $mess[$i] == ucfirst($mess2[$j])) {
                print_r('saasd!!!!!!!!!');
                sendPic($user['message']['chat']['id']);
                $z=true;
                break;
            }
        }
        if($z){
            break;
        }
    }
}

function changeRole($pass, $user){
    if($pass == 'keyWord') {
        $DB = connectBD();
        $str = "UPDATE  `u0512445_default`.`telegramBot` SET  `typeRole` =  'prepod' WHERE  `telegramBot`.`username` ='" . $user['message']['chat']['username']."'";
        $DB->exec($str);
        $DB=NULL;
        return "Ваша новая роль: преподаватель";

    }
    else{
        return "Неверный пароль";
    }
}
function getRole($user){
    $DB=connectBD();
    $str="SELECT typeRole FROM telegramBot WHERE username = '".$user['message']['chat']['username']."'";
    foreach ($DB->query($str) as $row){
        $role=$row['typeRole'];
    }
    return $role;
}

function setGroupsCont($user, $cmd){
    $groups='';
    for ($i=1;$i<count($cmd);$i++){
        $groups=$groups.' '.$cmd[$i];
    }

    $DB=connectBD();
    $str="UPDATE  `u0512445_default`.`telegramBot` SET  `groupsControl` =  '".$groups."' WHERE  `telegramBot`.`username` ='".$user['message']['chat']['username']."'";
    $DB->exec(/*"UPDATE 'u0512445_default'.'telegramBot' SET 'group' ='".$text."' WHERE 'telegramBot'.'username' = '".$user['message']['chat']['username'])."'"*/$str);
    $DB=NULL;
}
function getGroupsCont($user){
    $DB=connectBD();
    $str="SELECT groupsControl FROM telegramBot WHERE username = '".$user['message']['from']['username']."'";
    foreach ($DB->query($str) as $row){
        $text=$row['groupsControl'];
    }
    if($text) {
        print_r("\n GETgroupCont \n".$text."\n");
        return $text;
    }
    else{
        $text=" не найдена :(";
        return $text;
    }
}

function setGroup($user,$text){
    $DB=connectBD();
    $str="UPDATE  `u0512445_default`.`telegramBot` SET  `groups` =  '".$text."' WHERE  `telegramBot`.`username` ='".$user['message']['chat']['username']."'";
    if($DB->exec(/*"UPDATE 'u0512445_default'.'telegramBot' SET 'group' ='".$text."' WHERE 'telegramBot'.'username' = '".$user['message']['chat']['username'])."'"*/$str)){
    $DB=NULL;
    return true;
    }
    else{
        return false;
    }

}
function getGroup($user){
    $DB=connectBD();
    $str="SELECT groups FROM telegramBot WHERE username = '".$user['message']['from']['username']."'";
    foreach ($DB->query($str) as $row){
        $text=$row['groups'];
    }
    if($text) {
        return $text;
    }
    else{
        $text="не найдена";
        return $text;
    }

}
function inBase($user){
    $DB=connectBD();
    $str="SELECT username FROM `telegramBot` WHERE username = '".$user['message']['chat']['username']."'";
    foreach ($DB->query($str) as $row) {
        $checkUser=$row['username'];
    }
    if($checkUser){
        return true;
    }
    else{
        return false;
    }


}

function addUserBD($user){
    $DB=connectBD();
    $str="
    INSERT INTO  `u0512445_default`.`telegramBot` (
`first_name` ,
`chat_id` ,
`username` ,
`groups` ,
`typeRole` ,
`id`,
`groupsControl`
)
VALUES (
'".$user['message']['chat']['first_name']."',  '".$user['message']['chat']['id']."',  '".$user['message']['chat']['username']."',  '',  '', NULL,''
);";


    $DB->exec($str);
    $DB=NULL;
}
function addPrep($text){
    $DB=connectBD();
    $DB->exec("UPDATE telegramBot SET typeRole='prepod' WHERE username = '".$text."'");
    $DB=NULL;
}
function dispatchAll($text,$user){
    $DB=connectBD();
    $studentsID=array();
    /*$groups=array();*/
    /*foreach ($DB->query("SELECT groupControl FROM telegramBot WHERE username='".$user['message']['chat']['username']."'") as $row){

        $groups[]=$row['groupControl'];
    }*/
    $groups=getGroupsCont($user);
    $groups=explode(" ",trim($groups));

    $count=count($groups);
    for($t=0; $t < $count; $t++) {
        $str="SELECT chat_id FROM telegramBot WHERE groups='" . $groups[$t] . "'";
        foreach ($DB->query($str) as $row) {
            $studentsID[] = $row['chat_id'];
        }
    }
    $message='';
    for ($z=1;$z<count($text);$z++){
        $message=$message." ".$text[$z];
    }
    $message=$message."\n @".$user['message']['chat']['username'];
    for($i=0;$i<count($studentsID);$i++)
    {

        sendMessage($studentsID[$i],$message);
    }
    /*return $studentsID;*/

}
function dispatch($text, $user){
    $DB=connectBD();
    $studentsID=array();

    $str="SELECT chat_id FROM telegramBot WHERE groups='".$text[1]."'";
    foreach ($DB->query($str) as $row){
        print_r("Check this".$row['chat_id']);

        $studentsID[]=$row['chat_id'];
    }
    print_r("\n STUDDD::".$studentsID);
    /*$studentsID= $DB->query('SELECT chat_id FROM telegramBot WHERE group='.$text[1]);*/
    $DB=NULL;
    $message='';
    for($t=2;$t<count($text);$t++){
        $message=$message." ".$text[$t];

    }
    $message=$message."\n @".$user['message']['chat']['username'];
    for($i=0;$i<count($studentsID);$i++)
    {

        sendMessage($studentsID[$i],$message);
    }
    /*return $studentsID;*/
}
function getRasp2($user, $test){

    $date=''.date('Y-m-j');

    $myCurl = curl_init();

    $groupUs = getGroup($user);

    curl_setopt_array($myCurl, array(
        CURLOPT_URL => 'http://rasp.dmami.ru/site/group?group='.$test./*173-422*/'&session=0',
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

    if($resposnse["status"] == "ok"){
        $dayNum = date("w", time());//день недели

        $resp=$resposnse['grid'][$dayNum];




        $text="Расписание: \n";
        for($t=1;$t< count($resp);$t++){
            if(!empty($resp[$t])){
                $time=getTime($t);
                $audit='';
                for($z=0;$z<count($resp[$t][0]['auditories']);$z++){
                    if($resp[$t][0]['auditories'][$z]['title']) {
                        if($z>0){
                            $audit = $audit . '/' . $resp[$t][0]['auditories'][$z]['title'];
                        }else{
                            $audit = $audit . $resp[$t][0]['auditories'][$z]['title'];
                        }
                    }
                }
                $text=$text."[".$time."] \n";
                $text=$text."\t[Предмет] ".$resp[$t][0]['subject']."\n";
                $text=$text."\t[Преподаватель] ".$resp[$t][0]['teacher']."\n";
                $text=$text."\t[Аудитория] ".$audit."\n";
                $text=$text."\t[Занятие] ".$resp[$t][0]['type']."\n \n";
            }}

        if(empty($resp)){
            sendMessage($user["message"]["chat"]["id"],"Произошла ошибка, вероятно группа указана неверно, для нее нет расписания");
        }
        else{
            sendMessage($user["message"]["chat"]["id"],$text);
        }
    }else{
        sendMessage($user["message"]["chat"]["id"],"Произошла ошибка, вероятно группа указана неверно");
    }
}

function getRasp($user){

    $date=''.date('Y-m-j');

    $myCurl = curl_init();

    $groupUs = getGroup($user);

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

if($resposnse["status"] == "ok"){
    $dayNum = date("w", time());//день недели

    $resp=$resposnse['grid'][$dayNum];




    $text="Расписание: \n";
    for($t=1;$t< count($resp);$t++){
        if(!empty($resp[$t])){
            $time=getTime($t);
            $audit='';
            for($z=0;$z<count($resp[$t][0]['auditories']);$z++){
                if($resp[$t][0]['auditories'][$z]['title']) {
                    if($z>0){
                        $audit = $audit . '/' . $resp[$t][0]['auditories'][$z]['title'];
                    }else{
                    $audit = $audit . $resp[$t][0]['auditories'][$z]['title'];
                    }
                }
            }
            $text=$text."[".$time."] \n";
            $text=$text."\t[Предмет] ".$resp[$t][0]['subject']."\n";
            $text=$text."\t[Преподаватель] ".$resp[$t][0]['teacher']."\n";
            $text=$text."\t[Аудитория] ".$audit."\n";
            $text=$text."\t[Занятие] ".$resp[$t][0]['type']."\n \n";
        }}

    if(empty($resp)){
        if($groupUs == "не найдена"){
            $text='Не найдена ваша группа, попробуйте команду /group';
        }else{
        $text=$groupUs." Не найденно расписание для вашей группы"/*'Нет такой группы, либо расписание на этот месяц отсутствует'*/;}
        sendMessage($user["message"]["chat"]["id"],$text);
    }
    else{
        sendMessage($user["message"]["chat"]["id"],$text);
    }
}else{
    sendMessage($user["message"]["chat"]["id"],"Произошла ошибка, вероятно ваша группа установлена некорректно");
}
}


function getTime($var){
    switch ($var){
        case 1:
            return '9:00-10:30';
            break;

        case 2:
            return '10:40-12:10';
            break;

        case 3:
            return '12:40-13:50';
            break;

        case 4:
            return '14:30-16:00';
            break;

        case 5:
            return '16:10-17:40';
            break;

        case 6:
            return '17:50-19:20';
            break;

        case 7:
            return '??';
            break;

    }

}