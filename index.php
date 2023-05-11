<?php

require 'Telegram.php';
require 'Weather.php';
require 'Db.php';
require 'Keyboards.php';


// $primal = Telegram::getLatestUpdate()['deco'];

file_put_contents(__DIR__ . '/log.txt', file_get_contents('php://input'));

// $f = json_decode(file_get_contents('log.txt'), JSON_OBJECT_AS_ARRAY);

// var_dump($f);

$deco = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);




////////////////////////////////////

// $primal = Telegram::getLatestUpdate()['deco'];

var_dump($deco);


// if($deco['message']){
// 	$fname = $primal['message']['from']['first_name'];
// 	$chat_id = $primal['message']['chat']['id'];
// 	$msg = $primal['message']['text'];


// } else if($deco['callback_query']){
// 	$fname = $primal['callback_query']['from']['first_name'];
// 	$chat_id = $primal['callback_query']['from']['id'];
// 	$callback_data = $primal['callback_query']['data'];


////////////////////

// $res = Telegram::getLatestUpdate();

// $deco = json_decode($res, true);

$chat_id;
$trigger;
$callback_data;
$command_text;

if($deco['message']){
    $chat_id = $deco['message']['chat']['id'];
    $trigger = $deco['message']['message_id'];
    
    $is_command = $deco['message']['entities']['type'];
    $command_type = 'bot_command';
    $message_text = $deco['message']['text'];
}

if($deco['callback_query']){
    $chat_id = $deco['callback_query']['message']['chat']['id'];
    $callback_data = $deco['callback_query']['data'];
}

var_dump($callback_data);

$bot = new Telegram($chat_id, $trigger);
$db = new Db($chat_id);

print_r( '<pre>' . $res . '</pre>');

// if($is_command){

//     if($message_text == '/start'){
//         $bot->sendKeyboard('Welcome to the PHP ToDo list!', Keyboards::START);
//     }
// }

if($message_text == '/start'){
        $bot->sendKeyboard('Welcome to the PHP ToDo list!', Keyboards::START);
    }

if($message_text){
    //Set note
    if (preg_match('/^set_note:/i', $message_text)) {
       $note = str_replace('set_note:','',$message_text);
       $db->setNote($note);
       $bot->sendMessage('Note was saved');
    }
    //Delete note
    if (preg_match('/^delete_note:/i', $message_text)) {
        $note = str_replace('delete_note:','',$message_text);
        $db->deleteNote($note);
        $bot->sendMessage('Note was deleted');
     }
     //Delete remind
     if (preg_match('/^delete_remind:/i', $message_text)) {
        $note = str_replace('delete_remind:','',$message_text);
        $db->deleteReminder($note);
        $bot->sendMessage('Reminder was deleted');
     }
     //Set remind
    if (preg_match("/^'\d+'\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/", $message_text)){
        $extraction_pattern = "/^'(\d+)'/";
        if(preg_match($extraction_pattern, $message_text, $matches)){
            $id = $matches[1];
            $unixtime = strtotime( substr($message_text, strlen($matches[0])));
            $db->setReminder($id, $unixtime);
            $bot->sendMessage('Reminder was set');
        }
    }
    //Search note
    if (preg_match('/^search:/i', $message_text)) {
        $note = str_replace('search:','',$message_text);
        $message = $db->getLike($note);
        $bot->sendMessage($message);
     }
    //Change remind
    if(preg_match("/^change_remind:'\d+'\s*\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}$/", $message_text)){
        $prep = str_replace('change_remind:', '', $message_text);
        $extraction_pattern = "/^'(\d+)'/";
        if(preg_match($extraction_pattern, $prep, $matches)){
            $id = $matches[1];
            $unixtime = strtotime(substr($prep, strlen($matches[0])));
            $db->setReminder($id, $unixtime);
            $bot->sendMessage('Reminder was updated');
        }
        // $bot->sendMessage('Yep, that works');
    }
    //Get between
    if(preg_match("/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\*\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/", $message_text)){
        $datetime_array = explode('*', $message_text);
        $unixtime1 = strtotime($datetime_array[0]);
        $unixtime2 = strtotime($datetime_array[1]);
        $msg = $db->getBetween($unixtime1, $unixtime2);
        $bot->sendMessage($msg);
    }
}

if($callback_data){
    if($callback_data == 'start-show'){
        //Show notes
        $notes=$db->getNotes();
        $bot->sendMessage($notes);
    }

    $follow = 'Follow this example to make a note:';
    $empty_row = "\n";
    $empty_rows = "\n" . "\n";
    $notification = "You will get a notification if you did all correct";
    $make_sure = "Please make sure it starts with";
    $instruction = 'Use following examples';

    if($callback_data == 'note-set'){
        $msg = 'Follow this example to make a note:' . "\n" . "\n" . 'set_note:Your note here'. "\n" . "\n" . "Please make sure it starts with 'set_note:'" . "\n" . "You will get a notification if you did all correct";
        $bot->sendMessage($msg);
    }

    if($callback_data == 'remind-set'){
        $msg = 'Follow this example to make a reminder for a note:' . "\n" . "\n" . "'note_id'0000-00-00 00:00:00". "\n" . "\n" . "Please make sure it starts with a note id inside single quotes ''. Dont put space between single quotes and year. Put space between year and time" . "\n" . "You will get a notification if you did all correct" . "\n" . "\n" . "Example: '1'2023-06-05 13:23:45" . "\n" . 'It means, that notification will be sent on 5th of June 2023 at 1:23:45 PM';
        $bot->sendMessage($msg);
    }

    if($callback_data == 'note-delete'){
        $msg = 'Follow this example to delete a note:' . "\n" . "\n" . 'delete_note:Your note id here'. "\n" . "\n" . "Please make sure it starts with 'delete_note:' and dont put space after ':'" . "\n" . "You will get a notification if you did all correct";
        $bot->sendMessage($msg);
    }

    if($callback_data == 'note-search'){
        $msg = 'Follow this example to find a note:' . "\n" . "\n" . 'search:Your note text fragment here'. "\n" . "\n" . "Please make sure it starts with 'search:' and dont put space after ':'" . "\n" . "You will get a notification if you did all correct";
        $bot->sendMessage($msg);
    }

    if($callback_data == 'remind-change'){
        $msg = $follow . $empty_rows . "change_remind:'note_id'0000-00-00 00:00:00". $empty_rows . $make_sure . " a note id inside single quotes ''. Dont put space between single quotes and year. Put space between year and time" . $empty_row . $notification . $empty_rows . "Example: change_remind:'1'2023-06-05 13:23:45" . $empty_row . 'It means, that notification time of ID 1 will be updated to 5th of June 2023 at 1:23:45 PM';
        $bot->sendMessage($msg);
    }

    if($callback_data == 'remind-delete'){
        $msg = $follow . $empty_rows . "delete_remind:'note_id'" . $empty_rows . $make_sure . " a note id inside single quotes ''. Dont put space between colon and single quotes" . $empty_row . $notification . $empty_rows. "Example: delete_remind:'1'" . $empty_row . 'It means, that notification time will be removed from note with ID 1';
        $bot->sendMessage($msg);
    }
    if($callback_data == 'remind-between'){
        $msg = $instruction . $empty_rows . '0000-00-00 00:00:00*0000-00-00 00:00:00' . $empty_rows . '2000-01-01 00:00:00*2030-01-01 00:00:00';
        $bot->sendMessage($msg);
    }

    

    
}






?>