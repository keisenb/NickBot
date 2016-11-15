<?php
include 'config.php';
require_once 'twilio/vendor/autoload.php';
use Twilio\Rest\Client;

$db = new mysqli(DBHOST, DBUSER, DBPASS, DBTABLE);

$sid    = TSID;
$token  = TTOKEN;
$twilio = TNUMBER;

$client = new Client($sid, $token);

$number_array = array();
$sql          = "SELECT phone FROM numbers";

if ($db->connect_errno > 0) {
    die('Unable to connect to database [' . $db->connect_error . ']');
}

$result = $db->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $number_array[] = $row["phone"];
    }
} else {
    echo "0 results";
}

$result = shell_exec('curl http://hasnicktoldhisdadjoketoday.com/api/isyes');
if ($result == "YES") {
    foreach ($number_array as $number) {
        $sms = $client->account->messages->create("+1" . $number, array(
            'from' => $twilio,
            // the sms body
            'body' => "Nick has told his dad joke today."
        ));
    }
}

$db->close();
?>
