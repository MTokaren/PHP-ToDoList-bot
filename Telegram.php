<?php


class Telegram
{
    const LOCAL_TOKEN = '6140959186:AAH9EPi8BFGZt6iklYy-BdlSmpvHheJ0h6I/';
    const BASIC_URL = 'https://api.telegram.org/bot';
    protected $chat_id;
    protected $trigger;

    // static public function getLatestUpdate(){
    //     $data = ['offset'=>-1];
    //     $query = http_build_query($data);
    //     $res = file_get_contents(self::BASIC_URL . self::LOCAL_TOKEN . 'getUpdates?' . $query);
    //     return $res;
    // }

    public function __construct($chat_id, $trigger){
        $this->chat_id = $chat_id;
        $this->trigger = $trigger;
    }

    public function sendMessage($message){
        $data = ['chat_id'=>$this->chat_id, 'text'=>$message];
        $ch = curl_init(self::BASIC_URL . self::LOCAL_TOKEN . 'sendMessage?' . http_build_query($data));
        $a = curl_exec($ch);
        curl_close($ch);
    }

    public function sendKeyboard($message, $keyboard){

        $data = ['chat_id'=>$this->chat_id, 'text'=>$message, 'reply_markup'=>json_encode($keyboard)];
        $ch = curl_init(self::BASIC_URL . self::LOCAL_TOKEN . 'sendMessage?' . http_build_query($data));
        $a = curl_exec($ch);
        curl_close($ch);
    }

    // public function updateKeyboard($message, $keyboard){
    //     $data = ['chat_id'=>$this->chat_id, 'message_id'=>$this->trigger,'text'=>$message, 'reply_markup'=>json_encode($keyboard)];
    //     $ch = curl_init(self::BASIC_URL . self::LOCAL_TOKEN . 'editMessageReplyMarkup?' . http_build_query($data));
    //     $a = curl_exec($ch);
    //     curl_close($ch);
    // }
}