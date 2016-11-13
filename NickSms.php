<?php
require_once 'twilio/vendor/autoload.php';
use Twilio\Rest\Client;

$sid = "twilio sid";
$token = "twilio token";
$client = new Client($sid, $token);
$twilio = "twilio number";


$number = $_POST['From'];
$body = $_POST['Body'];
$text = strtolower($body);

header('Content-Type: text/xml');

 ?>

 <Response>
   <?php
   if ($text == "yes" || $text == "yes!" || $text == "nick" || $text == "yeah"){

     shell_exec("/var/www/kyle-eisenbarger.com/public_html/scripts/nick.sh");

     echo "<Message>Nick has told his dad joke today!</Message>";
   }
   else {
     echo "<Message>Hey ".$number."! You sent ".$body.". You should send the word 'Yes' to update the site!</Message>";
   }
   ?>
</Response>
