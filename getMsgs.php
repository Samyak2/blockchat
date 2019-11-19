<?php
require_once "dbtest.php";
require_once "genKeys.php";

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(empty(trim($_GET["sender"]))){
        $sender_err = "Please enter sender.";
    } else{
        $sender = htmlspecialchars(trim($_GET["sender"]));
    }

    if(empty(trim($_GET["receiver"]))){
        $receiver_err = "Please enter receiver.";
    } else{
        $receiver = htmlspecialchars(trim($_GET["receiver"]));
    }
    $sender2 = $receiver2 = $pubkey = $prikey = "";

    if(empty($sender_err) && empty($receiver_err)){
        // Prepare a select statement
        $sql = "SELECT sender, receiver, pubkey, prikey FROM " . DB_NAME . ".keys WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_sender, $param_receiver, $param_receiver, $param_sender);
            
            // Set parameters
            $param_sender = $sender;
            $param_receiver = $receiver;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) >= 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $_sender, $_receiver, $_pubkey, $_prikey);
                    if(mysqli_stmt_fetch($stmt)){
                        $sender2 = $_sender;
                        $receiver2 = $_receiver;
                        $pubkey = $_pubkey;
                        $prikey = $_prikey;
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                    // $prikey = genKeys($sender, $receiver);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);

    $url = SERVER_NAME . 'getUserMsgs';
    $data = array('sender' => urlencode($sender), 'receiver' => urlencode($receiver), 'prikey' => $prikey);

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
    if ($result === FALSE) { /* Handle error */ }
    $b = json_decode($result);
    $msgs = $b->{'messages'};
    echo json_encode(array('messages' => $msgs, 'pubkey' => $pubkey, 'prikey' => $prikey));
}
?>