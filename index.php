<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" href="css/topbar.css">
<title>BlockChat</title>
<meta charset="utf-8">
<style>
    html {
        background: url(images/bg-image.jpg) no-repeat center center fixed;
        background-size: cover;
    }

    #main {
        display: flex;
        flex-direction: column;
        flex: 1;
        font-size: larger;
        text-align: center;
        font-family: "Courier New", Courier, monospace;
    }

    p {
        padding: 0 20%;
        color: aquamarine;
    }

    h1, h2 {
        margin-bottom: 0%;
    }

    h1 {
        color: #b5a8fb;
    }

    h2 {
        color: aliceblue;
    }
</style>
<link rel="stylesheet" href="css/username_dropdown.css">
</head>
<body>
    <div id="maincontainer">
        <ul id="topbar">
            <li id="topbartitle">
                <a class="active" href="index.php">Blockchat</a>
            </li>
            <li id="topbarlink" class="dropbtn dropdown">
                    <a href="profilepage.php" id="username_topbar"><?php echo $_SESSION["username"]; ?></a>
                    <div class="dropdown-content">
                            <a href="logout.php" style="text-align: left;">Logout</a>
                    </div>
            </li>
            <li id="topbarlink">
                <a href="aboutus.html">About Us</a>
            </li>
            <li id="topbarlink">
                <a href="chatpage.php">Chat</a>
            </li>
            <li id="topbarlink">
                <a href="transfer.php">Transfer</a>
            </li>
        </ul>


        <div id="main" style="margin-top: 35px;">
            <h1>BlockChat</h1>
            <p>
                A different messaging application that uses
                Blockchain to secure your messages. We use a
                virtual currency system to transfer messages.
            </p>
            <h2>How does it work?</h2>
            <p>We use Blockchain technology to store and transfer
                messages. It is completely decentralized, so that your
                messages are not stored on just one server. Every node
                has a copy of the blockchain.
            </p>

            <h2>Does that mean everyone can see my messages?</h2>
            <p>No, your messages are safely encrypted using
            cryptography. Only you and the person you are messaging
            can read them.
            </p>

            <h2>What are coins?</h2>
            <p>
                Coin is a virtual currency that is used by the
                Blockchain to perform transactions. Every user gets a
                fixed amount of coins to start with. Coins cannot be
                bought and can be earned by mining or tranferring. A
                very minute amount of coins are used to send messages.
            </p>
        </div>
    </div>
    <script>
        topbar = document.getElementById("topbar");
        var contentPlacement = topbar.getBoundingClientRect().top + topbar.offsetHeight;
        document.getElementById("main").style["margin-top"] = contentPlacement + "px";
    </script>
</body></html>