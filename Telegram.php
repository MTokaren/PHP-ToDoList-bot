<?php


class Telegram
{
    const DICTIONARY = array(
        'not_listening'=>'',
        'set_note'=>'Enter your note message',
        'delete_note'=>'Enter note id to delete it',
        'delete_remind'=>'Enter note id to delete its remind',
        'set_remind'=>'Enter note id to edit',
        'search_note'=>'Enter string to search among notes',
        'set_between'=>'Set first time like following example: 2023-06-23 18:00:00',
        'set_time'=>'Enter time like following example: 2023-06-23 18:00:00',
        'set_second_time'=>'Set second time like following example: 2023-06-23 18:00:00'
    );

    const LOCAL_TOKEN = '6140959186:AAH9EPi8BFGZt6iklYy-BdlSmpvHheJ0h6I/';
    const BASIC_URL = 'https://api.telegram.org/bot';
    protected $chat_id;

    public function __construct(int $chat_id)
    {
        $this->chat_id = $chat_id;
    }

    public function setChatId(int $chat_id):self{$this->chat_id = $chat_id; return $this;}
    public function getChatId():int{return $this->chat_id;}

    public function sendMessage(string $message):void
    {
        $data = ['chat_id'=>$this->chat_id, 'text'=>$message];
        $ch = curl_init(self::BASIC_URL . self::LOCAL_TOKEN . 'sendMessage?' . http_build_query($data));
        $a = curl_exec($ch);
        curl_close($ch);
    }

    public function sendKeyboard(string $message, array $keyboard):void
    {

        $data = ['chat_id'=>$this->chat_id, 'text'=>$message, 'reply_markup'=>json_encode($keyboard)];
        $ch = curl_init(self::BASIC_URL . self::LOCAL_TOKEN . 'sendMessage?' . http_build_query($data));
        $a = curl_exec($ch);
        curl_close($ch);
    }
     
    public function setState(string $keyword):void
    {
        $_SESSION['state'] = $keyword;
        session_write_close();
        $this->sendMessage(self::DICTIONARY[$keyword]);
    }
}
