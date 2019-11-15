<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "dbtest.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Blockchat Mining Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="css/topbar.css">
  <link rel="stylesheet" href="css/username_dropdown.css">
</head>
    <body>
        <ul id="topbar">
            <li id="topbartitle">
                <a class="active">Blockchat</a>
            </li>
            <li id="topbarlink" class="dropbtn dropdown">
                    <a href="profilepage.html" id="username_topbar">Username</a>
                    <div class="dropdown-content">
                            <a href="Login_Page/index.html" style="text-align: left;">Logout</a>
                    </div>
            </li>
            <li id="topbarlink">
                <a href="aboutus.html">About Us</a>
            </li>
            <li id="topbarlink">
                <a href="chatpage.html">Chat</a>
            </li>
            <li id="topbarlink">
                <a href="transfer.html">Transfer</a>
            </li>
            <!-- <li id="topbarlink">
                    <a href="miningpage.html">Mining</a>
            </li>
            <li id="topbarlink">
                    <a href="blockchain.html">Blockchain</a> -->
            </li>
        </ul>
        <div class="container" id="main">
            <form method="POST" action="">
                <h1 align="center">Reset password</h1>
                <div class="row">
                    <div class="col-md-5" style=" margin: auto; width: 50%; padding: 10px; text-align: center;">
                        <input type="text" name="username" placeholder="Username or email"><br><br>
                        <button type="button" class="btn btn-success btn-lg btn-block">Get Reset Link</button>
                    </div>
                    <div class="col-md-2" style=" margin: auto; width: 50%; padding: 10px;">
                        <h1 align="center">OR</h1>
                    </div>
                    <div class="col-md-5" style=" margin: auto; width: 50%; padding: 10px; text-align: center;">
                        <!-- <input type="text" name="old password" placeholder="Old Password"><br><br> -->
                        <input type="text" name="new_password" placeholder="New Password"><br><br>
                        <span class="help-block"><?php echo $new_password_err; ?></span>
                        <input type="text" name="confirm_password" placeholder="Confirm New Password"><br><br>
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                        <button type="submit" class="btn btn-success btn-lg btn-block">Change Password</button>
                    </div>
                </div>
              </form>
            </div>

        </div>
        <script>
                topbar = document.getElementById("topbar");
                var contentPlacement = topbar.getBoundingClientRect().top + topbar.offsetHeight + 10;
                document.getElementById("main").style["margin-top"] = contentPlacement + "px";
        </script>
    </body>
</html>

