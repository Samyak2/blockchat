<?php
require_once "dbtest.php";

function checkIfKeyExists($sender, $receiver) {
    global $link;
    $msg = "";
    $sender2 = $receiver2 = $pubkey2 = $prikey2 = "";
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
                    $pubkey2 = $_pubkey;
                    $prikey2 = $_prikey;
                    $msg = $prikey2;
                }
            } else{
                // Display an error message if username doesn't exist
                $msg = false;
                // $prikey = genKeys($sender, $receiver);
            }
        } else{
            $msg = false;
        }
    }
    
    // Close statement
    mysqli_stmt_close($stmt);

    return $msg;
}

function genKeys($sender, $receiver) {
    global $link;
    $sender2 = $receiver2 = $pubkey2 = $prikey2 = "";

    $res = checkIfKeyExists($sender, $receiver);
    if($res != false) {
        return $res;
    }

    $url = SERVER_NAME . 'generateKeys';
    $data = array();

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
    $pubkey = $b->{'pubkey'};
    $prikey = $b->{'prikey'};


    if(empty($sender_err) && empty($receiver_err)){
        // Prepare a select statement
        $sql = "INSERT INTO " . DB_NAME . ".keys (`sender`, `receiver`, `pubkey`, `prikey`) VALUES (?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_sender, $param_receiver, $param_pubkey, $param_prikey);
            
            // Set parameters
            $param_sender = $sender;
            $param_receiver = $receiver;
            $param_pubkey = $pubkey;
            $param_prikey = $prikey;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
    return $prikey;
}

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

    genKeys($sender, $receiver);
}
?>