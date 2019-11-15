<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
require_once "dbtest.php";
if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(!isset($_GET["msg"]) || empty(trim($_GET["msg"]))){
        $msg_err = "Please enter msg.";
    } else{
        $msg = htmlspecialchars(trim($_GET["msg"]));
        echo "";
    }
}

$url = SERVER_NAME . 'getCoins';
$data = array('sender' => $_SESSION["username"]);

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
$coins = json_decode($result);

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
                <a href="index.php">Blockchat</a>
            </li>
            <li id="topbarlink" class="dropbtn dropdown">
                    <a href="profilepage.php" id="username_topbar"><?php echo $_SESSION["username"]; ?></a>
                    <div class="dropdown-content">
                            <a href="logout.php" style="text-align: left;">Logout</a>
                    </div>
            </li>
            <li id="topbarlink">
                <a href="aboutus.php">About Us</a>
            </li>
            <li id="topbarlink">
                <a href="chatpage.php">Chat</a>
            </li>
            <li id="topbarlink">
                <a class="active" href="transfer.php">Transfer</a>
            </li>
            <!-- <li id="topbarlink">
                    <a href="miningpage.html">Mining</a>
            </li>
            <li id="topbarlink">
                    <a href="blockchain.html">Blockchain</a> -->
            </li>
        </ul>
        <div class="container" id="main" style="font-family: 'Courier New', Courier, monospace;">
            <form action="sendtransaction.php" method="POST">
                <br /><br />
                <h1 align="left">Transfer Coins</h1>
                <div class="row">
                    <!-- <div class="col-md-5" style=" margin: auto; width: 50%; padding: 10px; text-align: center;">
                        <input type="text" name="username" placeholder="Username or email"><br><br>
                        <button type="button" class="btn btn-success btn-lg btn-block">Get Reset Link</button>
                    </div>
                    <div class="col-md-2" style=" margin: auto; width: 50%; padding: 10px;">
                        <h1 align="center">OR</h1>
                    </div> -->
                    <div class="col-md-5" style=" margin: 20px; width: auto; padding: 10px; text-align: left;">
                        <input type="text" name="sender" value="<?php echo $_SESSION["username"] ?>" hidden="true">
                        <input type="text" name="receiver" placeholder="Recipient username"><br><br>
                        <input type="text" name="value" placeholder="Amount"> Balance: <span id="numcoins"><?php echo $coins; ?></span> Coins<br><br>
                        <!-- <input type="text" name="confirm new password" placeholder="Confirm New Password"><br><br> -->
                        <button type="submit" class="btn btn-success btn-lg btn-block">Transfer</button>
                        <h3> <?php if(isset($msg)) echo $msg ?> </h3>
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

        <!-- <script>
            function getCoins() {
                var data = "sender=" + "<?php echo $_SESSION["username"] ?>";
                var httpRequest = new XMLHttpRequest;
                httpRequest.onreadystatechange = function(){
                    if (httpRequest.readyState === 4) { // Request is done
                        if (httpRequest.status === 200) { // successfully
                            callback(httpRequest.responseText); // We're calling our method
                        }
                    }
                };
                httpRequest.open('POST', "http://127.0.0.1:8000/getCoins");
                httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                httpRequest.send(data);
            }
            function callback(data) {
                numcoins = document.getElementById("numcoins");
                numcoins.textContent = data;
            }
        </script> -->
    </body>
</html>

