<?php












function switchCommand($user/*$text,$chatId*/){
    $role=checkRole($user);
    if($role == 'admin'){
        switchCommandAdmin($user);
    }
    elseif ($role == 'prepod'){
        switchCommandPrepod($user);
    }
    switchCommandPublic($user);
}






function  switchCommandAdmin($user){
    if($user['message']['text']) {
        $command = getCommand($user['message']['text']);
        switch ($command[0]){
            case '/addPrep':
                if($command[1]) {
                    addPrep($command[1]);
                }
                else{
                    print_r('error');
                }
                break;

            case '/dispatch':
                if($command[1] and $command[2] and $command[3]) {
                    dispatch($command,$user);
                }
                else{
                    $moyOtvet="Список команд для Препод Телеги: \n /dispatch [*Arg1] -- отправляет сообщение студентам группе или же всем подконтрольным группам студентов(прим: /dispatch 161-345);\n /groupsControl -- Подконтрольные студенты \n *-- необязательный аргумент ";
                    sendMessage($user['message']['chat']['id'],$moyOtvet);
                }

                break;
            case '/dispatchAll':
                if($command[1] and $command[2] and $command[3]) {
                    dispatchAll($command,$user);
                }
                else{
                    $moyOtvet="Список команд для Препод Телеги: \n /dispatch [*Arg1] [text] -- отправляет сообщение студентам группе или же всем подконтрольным группам студентов(прим: /dispatch 161-345);\n /groupsControl -- Подконтрольные студенты \n *-- необязательный аргумент ";

                    sendMessage($user['message']['chat']['id'],$moyOtvet);
                }

                break;
            case '/groupsControl':
                $moyOtvet='DONE! Теперь твоя подконтрольная группа:'.$command[1];
                if($command[1]) {
                    setGroupsCont($user, $command);
                    sendMessage($user['message']['chat']['id'], $moyOtvet);
                }
                else{
                    $res=getGroupsCont($user);
                    sendMessage($user['message']['chat']['id'], 'Твоя группа:'.$res);
                }
                break;
            case '/helpAdm':
                $moyOtvet="Список команд для Админ Телеги: \n /addPrep [Arg1] \n /dispatchAll  [Arg1] \n /dispatch [Arg1] -- отправляет сообщение студентам группе или же всем подконтрольным группам студентов(прим: /dispatch 161-345);\n /groupsControl [*Arg1] -- Подконтрольные студенты \n *-- необязательный аргумент ";

                sendMessage($user['message']['chat']['id'],$moyOtvet);
                break;


        }
    }
}





function  switchCommandPrepod($user){
    if($user['message']['text']){
        $command = getCommand($user['message']['text']);
        switch ($command[0]){
            case '/dispatch':
                if($command[1] and $command[2] and $command[3]) {
                    dispatch($command,$user);
                }
                else{
                    $moyOtvet="Список команд для преподавателей: \n /dispatch [номер группы] [сообщение] -- отправляет сообщение подконтрольной группе(прим: /dispatch 161-345);\n /dispatchAll [сообщение] -- отправляет сообщение всем подконтрольным группам;\n /groupsControl [номер группы](можно указать несколько: /groupControl 161-371 123-321) -- настроить подконтрольные группы;\n  /groupsControl -- список подконтрольных групп; \n";
                    sendMessage($user['message']['chat']['id'],$moyOtvet);
                }

                break;
            case '/dispatchAll':
                if($command[1] and $command[2]) {
                    dispatchAll($command,$user);
                }
                else{
                    $moyOtvet="Список команд для преподавателей: \n /dispatch [номер группы] [сообщение] -- отправляет сообщение подконтрольной группе(прим: /dispatch 161-345);\n /dispatchAll [сообщение] -- отправляет сообщение всем подконтрольным группам;\n /groupsControl [номер группы](можно указать несколько: /groupControl 161-371 123-321) -- настроить подконтрольные группы;\n  /groupsControl -- список подконтрольных групп; \n";

                    sendMessage($user['message']['chat']['id'],$moyOtvet);
                }

                break;
            case '/groupsControl':
                $moyOtvet='Отлично! Теперь ваша подконтрольная группа:'.$command[1];
                if($command[1]) {
                    setGroupsCont($user, $command);
                    sendMessage($user['message']['chat']['id'], $moyOtvet);
                }
                else{
                    $res=getGroupsCont($user);
                    sendMessage($user['message']['chat']['id'], 'Ваши подконтрольные группы:'.$res);
                }
                break;

            case '/helpPrep':
                $moyOtvet="Список команд для преподавателей: \n /dispatch [номер группы] [сообщение] -- отправляет сообщение подконтрольной группе(прим: /dispatch 161-345);\n /dispatchAll [сообщение] -- отправляет сообщение всем подконтрольным группам;\n /groupsControl [номер группы](можно указать несколько: /groupControl 161-371 123-321) -- настроить подконтрольные группы;\n  /groupsControl -- список подконтрольных групп; \n";

                sendMessage($user['message']['chat']['id'],$moyOtvet);
                break;

        }
    }
}







function  switchCommandPublic($user){
    if(!empty($user['message']['text']) and $user['message']['chat']['type'] == 'private'){
        $command = getCommand($user['message']['text']);

        switch ($command[0]){

            case '/start':
                $moyOtvet='Приветствую тебя, '.$user['message']['chat']['first_name'].', в приложении от студентов и для студентов Московского Политеха, которое, я надеюсь, облегчит тебе обучение. Напиши команду /help, чтобы увидеть список всех возможных команд';
                if(!inBase($user)){
                    addUserBD($user);}
                sendMessage($user['message']['chat']['id'],$moyOtvet);
                break;
            case '/group':
                /*$moyOtvet='Успешно! Твоя группа:'.$command[1];*/
                if($command[1]) {
                    if(setGroup($user, $command[1])){
                        $moyOtvet='Успешно! Твоя группа:'.$command[1];

                    }
                    else{
                        $moyOtvet='Ошибка! Не удалось привязать ваш аккаунт к группе:'.$command[1];

                    }
                    sendMessage($user['message']['chat']['id'], $moyOtvet);
                }
                else{
                    $res=getGroup($user);
                    sendMessage($user['message']['chat']['id'], 'Твоя группа:'.$res);
                }
                break;
            case '/help':
                $role=getRole($user);
                $moyOtvet="Список команд для пользователя: \n /group [номер группы] -- настроить свою группу(прим: /group 161-345);\n /group -- узнать свою группу;\n /rasp -- получить расписание на неделю;\n /rasp [номер группы] -- получить расписание на неделю для определенной группы;\n ";
                $mess=array(
                    array( 'KeyboardButton'=>array('text' => '1')),
                    array( 'KeyboardButton'=>array( 'text' => '2')) ,
                    array( 'KeyboardButton'=>array( 'text' => '3')),
                    array( 'KeyboardButton'=>array( 'text' => '4'))

                );
                if($role == 'prepod'){
                    $moyOtvet=$moyOtvet."\n /helpPrep -- помощь по командам преподавателя";
                }
                elseif ($role == 'admin'){
                    $moyOtvet=$moyOtvet."\n /helpAdm -- помощь по командам админа";
                }
                sendMessage2($user['message']['chat']['id'],$moyOtvet,$mess);
                /*sendMessage($user['message']['chat']['id'],$moyOtvet);*/
                break;
            case '/changeRole':
                $moyOtvet= changeRole($command['1'],$user);
                sendMessage($user['message']['chat']['id'],$moyOtvet);
                break;

            case '/rasp':
                if($command[1]){
                    getRasp2($user,$command[1]);
                }else{
                    getRasp($user);}

                break;
        }
        filtrWords($command,$user);


    }
}