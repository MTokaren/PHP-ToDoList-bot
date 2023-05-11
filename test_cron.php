<?php

require 'Telegram.php';
require 'Db.php';

$botTest = new Telegram (448082677, 'Matvey');


// $botTest->sendMessage('Cron script is working!'); //1




$query = 'SELECT * FROM notes';

$pdo = new PDO('mysql:host=localhost;dbname=arey103_weatherbot','arey103_weatherbot', 'weatherBOT2023');

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


$a = $pdo->query($query);
$b = $a->fetchAll(PDO::FETCH_ASSOC);





foreach ($b as $note){
    $current = time();
     if(!empty($note['unixtime'])){
         if ($current >= $note['unixtime']){
            $bot = new Telegram ($note['chat_id'],'a');
            $bot->sendMessage($note['note']);

            
            $querySetNull = 'UPDATE notes SET unixtime = :remindTime WHERE chat_id = :chat_id';
            $null = 0;
            $stmt = $pdo->prepare($querySetNull);
            $stmt->execute([':remindTime'=>$null, ':chat_id'=>$note['chat_id']]);
         }
     }
}
    