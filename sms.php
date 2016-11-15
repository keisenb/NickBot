<?php
include 'config.php';
require_once 'twilio/vendor/autoload.php';
use Twilio\Rest\Client;

$db = new mysqli(DBHOST, DBUSER, DBPASS, DBTABLE);

$sid    = TSID;
$token  = TTOKEN;
$client = new Client($sid, $token);
$twilio = TNUMBER;

$number = $_POST['From'];
$body   = $_POST['Body'];
$text   = strtolower($body);

if ($db->connect_errno > 0) {
    die('Unable to connect to database [' . $db->connect_error . ']');
}

header('Content-Type: text/xml');
?>

 <Response>
   <?php

//checks the site and returns a confirmation message with the result.
//option = string
function response($option)
{
    $response = shell_exec('curl http://hasnicktoldhisdadjoketoday.com/api/isyes');

    if ($option == "update") {
        if ($response == "YES") {
            echo "<Message>Updated the site. Nickpls.</Message>";
        } else {
            echo "<Message>There was an error updating the site.</Message>";
        }
    } else if ($option == "check") {
        if ($response == "YES") {
            echo "<Message>Nick has told his dad joke today!</Message>";
        } else {
            echo "<Message>Nick has yet to tell a dad joke today.</Message>";
        }
    }
}

if ($text == "yes" || $text == "yee" || $text == "nick" || $text == "nickpls") {
    //updates the site to say yes
    shell_exec('curl http://hasnicktoldhisdadjoketoday.com/updateyes');
    response("update");
} else if ($text == "admin") {
    //resets the site to say no
    shell_exec('curl http://hasnicktoldhisdadjoketoday.com/updateno/jakeihatethatyoumademeaddthis');
    response("check");
} else if ($text == "sub") {
    //add user to subscribers list
    $sql = "INSERT INTO numbers (id, phone)
    VALUES (NULL, " . $number . ")";

    if ($db->query($sql) === TRUE) {
        echo "<Message>Added you to the subscribers list!</Message>";
    } else {
        echo "<Message>There was a problem with the request. Try again.</Message>";
    }
} else if($text == "unsub") {
    //remove user from the subscribers list
    $sql = "DELETE FROM numbers WHERE phone=" . $number;
    if ($db->query($sql) === TRUE) {
        echo "<Message>Unsubscribed successfully</Message>";
    } else {
        echo "<Message>Error removing you from the subscribers list</Message>";
    }
} else if ($text == "check" || $text == "?") {
    response("check");
} else if($text == "wat") {
    echo "<Message>Hi! To check the site say 'check'. To update the site say 'nickpls'.
    To subscribe for updates say 'sub'. To unsubscrube say 'unsub'. --Dad</Message>";
} else { //error
    echo "<Message>Hey " . $number . "! You sent " . $body . ". Send the word 'wat' for a list of commands you can send me!</Message>";
}
$db->close();
?>
</Response>
