<?php
require_once "dbtest.php";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender = $_POST["sender"];
    $receiver = $_POST["receiver"];
    $msg = $_POST["msg"];
    $pubkey = $_POST["pubkey"];
    $pubkey = urlencode($pubkey);

    $url = SERVER_NAME . 'new_transaction';
    $data = array('sender' => $sender, 'receiver' => $receiver, 'message' => $msg, 'pubkey' => $pubkey);

    $options = array(
        'http' => array(
            'header'  => "Content-Type: application/json\r\n" . "Accept: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { /* Handle error */ }
}
?>