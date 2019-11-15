<?php
require_once "dbtest.php";
// require_once "genKeys.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["sender"]))){
        $sender_err = "Please enter sender.";
    } else{
        $sender = htmlspecialchars(trim($_POST["sender"]));
    }

    if(empty(trim($_POST["receiver"]))){
        $receiver_err = "Please enter receiver.";
    } else{
        $receiver = htmlspecialchars(trim($_POST["receiver"]));
    }

    if(empty(trim($_POST["prikey"]))){
        $prikey_err = "Please enter prikey.";
    } else{
        $prikey = urlencode(trim($_POST["prikey"]));
    }

    if(empty(trim($_POST["timestamp"])) && $_POST["timestamp"] != "0"){
        $timestamp_err = "Please enter timestamp.";
    } else{
        $timestamp = htmlspecialchars(trim($_POST["timestamp"]));
    }

    $url = SERVER_NAME . 'getNewReceivedMsgs';
    $data = array('sender' => $sender, 'receiver' => $receiver, 'prikey' => $prikey, 'timestamp' => $timestamp);

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { /* echo $result; */ }
    // print_r($data);
    echo $result;
    // $b = json_decode($result);
    // $msgs = $b->{'messages'};
    // echo json_encode(array('messages' => $msgs));
}
?>