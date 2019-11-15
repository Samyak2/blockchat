<?php
require_once "dbtest.php";

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(empty(trim($_GET["sender"]))){
        $sender_err = "Please enter sender.";
    } else{
        $sender = htmlspecialchars(trim($_GET["sender"]));
    }
    $sender2 = $receiver = $pubkey = $prikey = "";
    $result = array();

    if(empty($sender_err)){
        // Prepare a select statement
        $sql = "SELECT sender, receiver, pubkey, prikey FROM blockchat.keys WHERE (sender = ?) OR (receiver = ?)";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_sender, $param_sender);
            
            // Set parameters
            $param_sender = $sender;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                mysqli_stmt_bind_result($stmt, $_sender, $_receiver, $_pubkey, $_prikey);
                while(mysqli_stmt_fetch($stmt)) {
                    array_push($result, array($_sender, $_receiver, $_pubkey, $_prikey));

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

    echo json_encode($result);
}
?>