<?php

class Db
{
    protected $chat_id;
    protected $pdo;

    public function __construct($chat_id){
        $this->chat_id = $chat_id;
        $this->pdo = new PDO('mysql:host=localhost;dbname=arey103_weatherbot','arey103_weatherbot', 'weatherBOT2023');
    }

    public function saveChatId(){

        $query = "SELECT * FROM notes WHERE chat_id = :chat_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':chat_id'=>$this->chat_id]);
        $res = $stmt->fetchAll();



        if(!$res){
            $query = "INSERT INTO notes SET chat_id=:chat_id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':chat_id'=>$this->chat_id]);
        } 
    }

    public function getNotes(){
        $query = "SELECT * FROM notes WHERE chat_id = :chat_id AND note IS NOT NULL";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':chat_id'=>$this->chat_id]);
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        $message = '';
        if(empty($res)){
            $message = "You don't have any notes!";
        } else {
            foreach ($res as $row){
                $message .= 'ID: ' . $row->id . '; Note: ' . $row->note . "\n";
            }
        }
        return $message;
    }

    public function setNote($note){
        $query = 'INSERT INTO notes (chat_id, note) VALUES (:chat_id, :note)';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':note'=>$note, ':chat_id'=>$this->chat_id]);
    }

    public function deleteNote($id){
        $query = "DELETE FROM notes WHERE id = :id AND chat_id = :chat_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id'=>$id, ':chat_id'=>$this->chat_id]);
    }

    public function setReminder($id, $unixtime){
        $query = "UPDATE notes SET unixtime = :unixtime WHERE id = :id AND chat_id = :chat_id ";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id'=>$id, ':chat_id'=>$this->chat_id, ':unixtime'=>$unixtime]);

    }

    public function getLike($substring){
        $note = "%" . $substring . "%";
        $query = "SELECT * FROM notes WHERE chat_id = :chat_id AND note LIKE :note";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':chat_id'=>$this->chat_id, ':note'=>$note]);
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        $message = '';
        if(empty($res)){
            $message = "No matches found!";
        } else {
            foreach ($res as $row){
                $message .= 'ID: ' . $row->id . '; Note: ' . $row->note . "\n";
            }
        }
        return $message;
    }

    public function deleteReminder($id){
        $unixtime = NULL;
        $query = "UPDATE notes SET unixtime =:unixtime WHERE id=:id AND chat_id = :chat_id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id'=>$id, ':chat_id'=>$this->chat_id, ':unixtime'=>$unixtime]);
    }

    public function getBetween($unixtime1, $unixtime2){
        $query = "SELECT * FROM notes WHERE chat_id = :chat_id AND unixtime BETWEEN :unixtime1 AND :unixtime2";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':chat_id'=>$this->chat_id, ':unixtime1'=>$unixtime1, ':unixtime2'=> $unixtime2]);
        $res = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach ($res as $row){
                $message .= 'ID: ' . $row->id . '; Note: ' . $row->note .  '; Remind: '. date('Y-m-d H:i:s', $row->unixtime) ."\n";
            }
        return $message;

    }
}