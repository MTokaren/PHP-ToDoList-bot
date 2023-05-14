<?php

require 'Telegram.php';
require 'Weather.php';
require 'Db.php';
require 'Keyboards.php';


$deco = json_decode(file_get_contents('php://input'), JSON_OBJECT_AS_ARRAY);

$chat_id;
$trigger;
$callback_data;
$command_text;
$default_state = 'not_listening';

if($deco['message'])
{
    $chat_id = $deco['message']['chat']['id'];
    $is_command = $deco['message']['entities']['type'];
    $command_type = 'bot_command';
    $message_text = $deco['message']['text'];
}

if($deco['callback_query'])
{
    $chat_id = $deco['callback_query']['message']['chat']['id'];
    $callback_data = $deco['callback_query']['data'];
}

session_id($chat_id);
session_start();

$bot = new Telegram($chat_id);
$db = new Db($chat_id);


if($message_text == '/start')
{
    $bot->sendKeyboard('Welcome to the PHP ToDo list!', Keyboards::START);
    $bot->setState('not_listening');
    session_write_close();
}

else if($message_text)
{

    //Set note (d0ne)
    if ($_SESSION['state'] == 'set_note')
    {
        $db->setNote($message_text);
        $bot->sendMessage('Note was saved');
        $_SESSION['state'] = $default_state;
        session_write_close();
    }

    //Delete note (done)
    if ($_SESSION['state'] == 'delete_note')
    {
     if(is_numeric($message_text))
     {
        $db->deleteNote($message_text);

        $bot->sendMessage('Note was deleted');
        $_SESSION['state'] = $default_state;
        session_write_close();
     } else
     {
        $bot->sendMessage('Invalid data type');
     }   
    }

     //Delete remind (done)
     if ($_SESSION['state'] == 'delete_remind')
    {
        if(is_numeric($message_text))
        {
           $db->deleteReminder($message_text);
           $bot->sendMessage('Reminder was deleted');
        } else
        {
           $bot->sendMessage('Invalid data type');
        }  
    }

     //Set remind:id (done)
     if ($_SESSION['state'] == 'set_remind' && is_numeric($message_text))
    {
     $_SESSION['note_id'] = $message_text;
     $bot->setState('set_time');
    }
    
    
     //Set remind:time (done)
     if ($_SESSION['state'] == 'set_time' && preg_match("/^(\d{4})-(\d{2})-(\d{2})\s(\d{2}):(\d{2}):(\d{2})$/", $message_text))
    {
     $id = $_SESSION['note_id'];
     $unixtime = strtotime($message_text);
     $db->setReminder($id, $unixtime);
     $_SESSION['note_id'] = '';
     $bot->sendMessage('Reminder was set');
     $bot->setState('not_listening');
    }
    
    //Search note (done)
    
    if ($_SESSION['state'] == 'search_note')
    {
        $message = $db->getLike($message_text);
        $bot->sendMessage($message);
    }

    //Get between:first
    if ($_SESSION['state'] == 'set_between' && preg_match("/^(\d{4})-(\d{2})-(\d{2})\s(\d{2}):(\d{2}):(\d{2})$/", $message_text))
    {
     $unixtime = strtotime($message_text);
     $_SESSION['unix'] = $unixtime;
     $bot->setState('set_second_time');
    }
    
    //Get between:second
    if ($_SESSION['state'] == 'set_second_time' && preg_match("/^(\d{4})-(\d{2})-(\d{2})\s(\d{2}):(\d{2}):(\d{2})$/", $message_text))
    {
        $first = $_SESSION['unix'];
        $second = strtotime($message_text);
        $msg = $db->getBetween($first, $second);
        $bot->sendMessage($msg);
        $_SESSION['unix'] = '';
        $bot->setState('not_listening');
    }
}

else if ($callback_data == 'start-show')
{
   //Show notes
   $notes=$db->getNotes();
   $bot->sendMessage($notes);
}
else if ($callback_data == 'show-state')
{
    $bot->sendMessage($_SESSION['state']);
}
else if ($callback_data)
{
    $bot->setState($callback_data);
}

?>