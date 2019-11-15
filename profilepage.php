<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "dbtest.php";
$url = SERVER_NAME . 'getCoins';
$data = array('sender' =>  $_SESSION["username"]);

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
// print_r($a);
// $result = (explode('}',explode(':',$result)[1])[0]);
// var_dump($result);
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/topbar.css">
<meta charset="utf-8"/>
<style>


html, body {
        height: 100%;
        margin: 0;
        background-color: aliceblue;
    }

    #maincontainer {
        display: flex;
        flex-flow: column wrap;
        overflow-x: hidden;
        height: 100%;
    }

    #profileinfo {
        margin: 25px;
        padding: 5px;
    }

    img#profpic {
        display: inline-block;
        vertical-align: middle;
        height: 100px;
        width: 100px;
        margin: 5px 5px 5px 5px;
        padding: 1px 1px;
        border-radius: 50%;
        border: 2px solid #333333;
        /* vertical-align: middle; */
    }

    div#namecoins {
        display: inline-block;
        vertical-align: middle;
    }

    div#username {
        display: inline-block;
        font-size: larger;
        margin: 5px;
        font-size: xx-large;
        /* vertical-align: middle; */
    }

    div#coins {
        /* display: inline-block; */
        font-size: large;
        font-weight: bold;
        padding: 5px;
    }

    span#numcoins {
        font-weight: normal;
    }

    div#profkeys h3, #showkeys {
        display: inline-block;
        padding-right: 1em;
    }

    div#profkeys p {
        font-weight: bold;
        padding-left: 1em;
    }

    span#pubkey, span#prikey {
        font-weight: normal;
    }

    #infoactions {
        display: flex;
        flex-direction: row;
    }

    #profileinfo {
        flex: 1 0 50%;
    }

    #accountactions {
        flex: 1 0 50%;
        font-size: larger;
        padding-left: 10px;
        border-left: 1px solid;
        line-height: 50px;
    }

    #chatkeys {
        padding: 20px 20px;
        border-top: 1px solid;
    }

    #keytable {
        width: 100%;
        text-align: center;
    }

    #keytable, #keytable th, #keytable td {
        border: 1px solid black;
        border-collapse: collapse;
        padding: 10px;
        /* white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden; */
    }

    #keytable th {
        background-color: aliceblue;
        width: auto;
    }

    #keytable td {
        background-color: lightgoldenrodyellow;
    }

    #caption {
        font-size: larger;
    }

    .key {
        /* text-overflow: ellipsis; */
        overflow: auto;
        /* white-space: nowrap; */
    }
</style>
<link rel="stylesheet" href="css/username_dropdown.css">
<script>
    var pubkey = "abc";
    var prikey = "def";
</script>

</head>
<body>
    <div id="maincontainer">
        <ul id="topbar">
            <li id="topbartitle">
                <a href="index.php">Blockchat</a>
            </li>
            <li id="topbarlink" class="dropbtn dropdown">
                <a class="active" href="profilepage.php" id="username_topbar"><?php echo $_SESSION["username"]; ?></a>
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
                <a href="transfer.php">Transfer</a>
            </li>
        </ul>

        <div id="main">
            <div id="infoactions">
                <div id="profileinfo">
                    <div>
                        
                        <img id="profpic" src="images/person.png" />
                        
                        <div id="namecoins">
                            <div id="username">
                                <?php echo $_SESSION["username"]; ?>
                            </div>
                            <div id="coins">
                                Coins: <span id="numcoins"><?php echo $coins; ?></span>
                            </div>
                        </div>
                    </div>
                    <!-- <div id="profkeys">
                        <h3>Keys</h3> <button id="showkeys" onclick="showkeys()">Show Keys</button>
                        <p>Public Key: <span id="pubkey">************</span></p>
                        <p>Private Key: <span id="prikey">************</span></p>
                    </div> -->
                </div>

                <div id="accountactions">
                    <h3>Account Actions</h3>

                    <ul>
                        <li>Want to earn more coins? <a href="miningpage.html">Help by mining!</a></li>
                        <li><a href="passwordreset.php">Change your password</a></li>
                        <li><a style="color: red;" href="deletepage.html">Delete or Deactivate Account</a></li>
                    </ul>
                </div>
            </div>

            <div id="chatkeys">
                <p id="caption">Per chat keys:</p>
                <table id="keytable" style="table-layout: fixed;">
                    <tr>
                        <th style="width: 60px;">Name</th>
                        <th>Public Key</th>
                        <th>Private Key</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <script>
        topbar = document.getElementById("topbar");
        var contentPlacement = topbar.getBoundingClientRect().top + topbar.offsetHeight;
        document.getElementById("main").style["margin-top"] = contentPlacement + "px";
    </script>

    <script>
        function addchatkey(name, key) {
            var keytable = document.getElementById("keytable");
            var row = keytable.insertRow(-1);
            var data = row.insertCell(-1);
            data.textContent = name;
            var data = row.insertCell(-1);
            data.textContent = key;
        }
    </script>

    <script>
        function showkeys() {
            pubkeyel = document.getElementById("pubkey");
            prikeyel = document.getElementById("prikey");
            pubkeyel.textContent = pubkey;
            prikeyel.textContent = prikey;
        }
    </script>

    <script src="js/getKeys.js"></script>
    <script>
        getKeys("<?php echo $_SESSION["username"]; ?>")
    </script>
</body>
</html>
