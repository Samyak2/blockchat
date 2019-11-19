<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "dbtest.php";

$url = SERVER_NAME . 'getUsers';
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
$a = json_decode($result);

?>
<!DOCTYPE html>
<html>
    <head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/jquery.color-animation/1/mainfile"></script>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/topbar.css">
    <title>BlockChat</title>
    <script>
        $(document).ready(function(){
            console.log("Ready");
            $("#search").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#leftcolumn .chat").filter(function() {
                    // console.log($(this).children(".personnamerecent").children(".name").text());
                    if($(this).children(".personnamerecent").children(".name").text().toLowerCase().indexOf(value) > -1) {
                        $(this).slideDown("slow");
                    }
                    else {
                        $(this).slideUp("slow");
                    }
                    // $(this).toggle(($(this).children(".personnamerecent").children(".name").text().toLowerCase().indexOf(value) > -1))
                });
            });

            // $(".chat").hover(
            //     function () {
            //         $(this).animate({backgroundColor:'#4d9ee686', boxShadow:'5px 5px 5px'},300);
            //     },
            //     function () {
            //         $(this).animate({backgroundColor:'#b4d6ff'},"fast");
            //     }
            // )
        });

        $(document).ready(function(){
            $("#leftcolumn").animate({ top: '0'}, 'slow');
        });
    </script>
<style>
#chattop {
  /* border: 1px solid #222222; */
  margin: 5px 10px;
  background-color: #d5f2ff;
  /* overflow: hidden; */
  /* flex: 0 0 10%; */
  box-shadow: 7px 8px #2270ff;
}

#chatmessages {
  flex: 1 0 10%;
  margin: 4px 5px 1px 5px;
  overflow-x: hidden;
  background-color: #dcedff09;
}



div.message.received {
  background-color: lightblue;
  text-align: left;
  float: left;
  box-shadow: 7px 6px blue;
  border: none;
  margin: 10px;
  padding: 11px;
  border-radius: 0px;
  max-width: 60%;
  display: flex;
  flex: 1 1 auto;
  clear: both;
}

div.right {
  flex: 1 0 70%;
  background-color: #ffffff;
  display: flex;
  flex-flow: column nowrap;
  overflow: hidden;
}


div.sent.message {
  background-color: #f7d324;
  float: right;
  box-shadow: 6px 6px #e67246;
  border: none;
  margin: 10px;
  padding: 11px;
  border-radius: 0px;
  max-width: 60%;
  display: flex;
  flex: 1 1 auto;
  clear: both;
}

html, body {
        height: 100%;
        margin: 0;
    }

    #maincontainer {
        display: flex;
        flex-flow: column wrap;
        overflow: hidden;
        height: 100%;
    }

    #main {
        display: flex;
        flex-direction: row;
        flex: 1;
        overflow: hidden;
    }

    div.left {
        flex: 1 0 30%;
        background-color: hsla(240, 7.9%, 53.1%, 0.92);
        overflow: auto;
        top: 1000px;
        position: relative;
        max-width: 30%;
        min-width: 20%;
    }

    div.right {
        flex: 1 0 70%;
        background-color: #ffffff;
        display: flex;
        flex-flow: column nowrap;
        overflow: hidden;
        background-image: url(images/chat-background.jpg);
        background-size: cover;
    }

    .chat {
    background-color: #b4d6ff;
    overflow: auto;
    /* margin: 5px 5px 5px 5px;
    box-shadow: 3px 3px #038bff; */
    margin: 0px 0px 0px 0px;
    box-shadow: 1px 1px #000;
    /* transition-duration: 0.6s; */
    cursor: pointer;
    width: 100%;
    text-align: left;
    padding: 0;
    border: 1px solid gray;
    font-size: large;
    display: flex;
    flex-direction: row;
    align-items: center;
    /* display: flex; */
    /* height: 62px; */
    }

    .chat:hover {
        box-shadow: 2px 2px 2px black;
        background-color: #a7cbf7;
    }


    img.profpic {
        /* display: inline-block; */
        /* float: left; */
        vertical-align: middle;
        height: 50px;
        width: 50px;
        padding: 3px 3px 3px 3px;
        border-radius: 50%;
        border: 1px solid #222222;
        margin: 2px;
    }

    div.personnamerecent {
        /* float: left; */
        /* display: inline-block; */
        /* vertical-align: middle; */
        height: 100%;
        max-width: calc(100% - 70px);
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    p.person {
        margin: 0 0 0 5px;
    }

    p.name {
        font-weight: bold;
    }

    #chattop {
        border: 1px solid #222222;
        margin: 5px 10px;
        /* overflow: hidden; */
        /* flex: 0 0 10%; */
        border-radius: 10px;
        position: relative;
        left: 100%;
    }

    #profpic {
        height: 50px;
        width: 50px;
        display: inline-block;
        vertical-align: middle;
        padding: 5px 5px 5px 5px;
    }

    #personname {
        display: inline-block;
        vertical-align: middle;
    }

    #chatmessages {
        flex: 1 0 10%;
        /* margin: 10px; */
        /* overflow: auto; */
    }

    div.message {
        border: 1px solid #333333;
        margin: 5px;
        padding: 5px;
        border-radius: 10px;
        max-width: 60%;
        display: flex;
        flex: 1 1 auto;
        clear: both;
        overflow-wrap: anywhere;
    }

    div.received {
        background-color: lightblue;
        text-align: left;
        float: left;
        position: relative;
        right: 100%;
    }

    div.sent {
        background-color: lightpink;
        float: right;
        position: relative;
        left: 100%;
    }

    #messageinput {
        width: 100%;
        height: 30px;
        margin: 5px 5px;
        /*! overflow: hidden; */
        vertical-align: middle;
        padding-bottom: 5px;
        position: relative;
        left: 100%;
    }

    #msginputbox {
        display: inline-block;
        width: calc(100% - 50px);
        height: 30px;
        border-radius: 10px;
        font-size: large;
        vertical-align: middle;
    }

    #msgsend {
        display: inline-block;
        width: 32px;
        height: 32px;
        vertical-align: middle;
        border-radius: 50%;
        background-image: url(images/send-button.png);
        background-repeat: no-repeat;
        background-position: left;
        /*! padding-left: 30px; */
        background-size: 30px;
        padding: 1px;
    }

    #search {
        padding: 5px;
        /* margin: 5px; */
        width: 100%;
    }


    .float {
        position: absolute;
        width: 60px;
        /* height: 60px; */
        /* bottom: 40px; */
        right: 10%;
        /* background-color: #0C9; */
        /* color:  */
        /* #FFF; */
        /* border-radius: 50px; */
        text-align: center;
        /* box-shadow: 2px 2px 3px #999; */
        float: right;
        top: 90%;
    }

    #addchatbtn {
        border-radius: 50%;
        height: 50px;
        width: 50px;
        box-shadow: 2px 2px 2px
        darkblue;
        border: 1px
        black;
        background-color:
        lightblue;
        cursor: pointer;
        background-image: url(images/plus.png);
        background-size: 20px;
        background-repeat: no-repeat;
        background-origin: content-box;
        background-position: center;
    }

    #addchatbtn:hover {
        box-shadow: 3px 3px 3px darkblue;
        border: 2px black;
    }
</style>
<link rel="stylesheet" href="css/username_dropdown.css">
</head>
<body>
    <div id="maincontainer">
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
                <a href="aboutus.html">About Us</a>
            </li>
            <li id="topbarlink">
                <a class="active" href="chatpage.php">Chat</a>
            </li>
            <li id="topbarlink">
                <a href="transfer.php">Transfer</a>
            </li>
        </ul>


        <div id="main" style="margin-top: 35px;">
            <div class="column left" id="leftcolumn">
                <input type="text" id="search" name="search" placeholder="Search....">
                <div class="float">
                    <button id="addchatbtn" onclick="addchat2()" value="AddChat"></button>
                </div>
            </div>
            <div class="column right" id="rightcolumn">
                <center style="height: 100%;">
                    <h3 style="color: aliceblue;height: 100%;position: relative;top: 30%;text-shadow: 1px 1px black;">
                        Select a chat from the left or start a new chat to start messaging
                        <!-- <?php
                            print_r($a);
                        ?> -->
                    </h3>
                </center>
            </div>
        </div>
    </div>
    <script>
        topbar = document.getElementById("topbar");
        var contentPlacement = topbar.getBoundingClientRect().top + topbar.offsetHeight;
        document.getElementById("main").style["margin-top"] = contentPlacement + "px";
    </script>

    <script>
        function addchat2() {
            var receiver = prompt("Enter username of the other person:");
            if(receiver.trim() == "") {
                alert("Invalid username");
                return 0;
            }

            var data = "sender=" + "<?php echo $_SESSION["username"] ?>" + "&receiver=" + receiver;
            var httpRequest = new XMLHttpRequest;
            httpRequest.onreadystatechange = function(){
                if (httpRequest.readyState === 4) { // Request is done
                    if (httpRequest.status === 200) { // successfully
                        callback(httpRequest.responseText); // We're calling our method
                    }
                }
            };
            httpRequest.open('POST', "genKeys.php");
            httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            httpRequest.send(data);
            function callback(data) {
                addchat(receiver);
            }
        }


        function sliceRecent(msg) {
            if(msg.length > 20) return msg.slice(0, 20) + "...";
            else return msg;
        }

        function addchat(name)
        {
            recentMsg = "";
            person = new Object();
            person.name = name;//prompt("Enter person name:");
            person.recentMessage = sliceRecent(recentMsg);//prompt("Enter recent message:");
            left_column = document.getElementsByClassName("column left")[0];
            // form = document.createElement("form");
            // form.action = "";
            // form.method = "POST";
            chat = document.createElement("button");
            chat.type = "submit";
            chat.name = "username";
            chat.value = name;
            chat.className = "chat";
            chat.onclick = loadChat;
            chat_img = document.createElement("img");
            chat_img.className = "profpic";
            chat_img.src = "images/person.png";
            chat.appendChild(chat_img);
            chat_info = document.createElement("div");
            chat_info.className = "personnamerecent";
            chat_info.innerHTML += "<p class=\"person name\">" + person.name + "</p> <p class=\"person recentmessage\">" + person.recentMessage + "</p>";
            chat.appendChild(chat_info);
            // chat.innerHTML += "<img class=\"profpic\" src=\"person.png\" />";
            // chat.innerHTML += "<div class=\"personnamerecent\">" \
            // "<p class=\"person name\">" + person.name + "</p>" + \
            // <p class=\"person recentmessage\">" + person.recentMessage + "</p>";
            // </div>";
            // form.appendChild(chat);
            left_column.appendChild(chat);
        }

        function addMsg() {
            inputbox = document.getElementById("msginputbox");
            msg = inputbox.value;
            inputbox.value = "";
            if(msg.trim() == "") {
                return false;
            }

            var data = "sender=" + "<?php echo $_SESSION["username"] ?>" + "&receiver=" + document.getElementById("personname").textContent + "&msg=" + msg + "&pubkey=" + window.pubkey;
            var httpRequest = new XMLHttpRequest;
            httpRequest.onreadystatechange = function(){
                if (httpRequest.readyState === 4) { // Request is done
                    if (httpRequest.status === 200) { // successfully
                        callback(httpRequest.responseText); // We're calling our method
                    }
                }
            };
            httpRequest.open('POST', "sendMsg.php");
            httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            httpRequest.send(data);
            function callback(text) {
                newMsg = document.createElement("div");
                newMsg.className = "message sent";
                newMsg.textContent = msg;
                chatmessages = document.getElementById("chatmessages");
                chatmessages.appendChild(newMsg);
                $("#chatmessages").children(":last-child").animate({ left: '0'}, 'fast');
                chatmessages.scrollTop = chatmessages.scrollHeight;
                current_person_name = document.getElementById("personname").textContent;
                // chats[current_person_name].push([1, msg])
                allchats = document.getElementsByClassName("personnamerecent");
                for(var i=0; i<allchats.length; ++i) {
                    if(allchats[i].getElementsByClassName("person name")[0].textContent == current_person_name) {
                        allchats[i].getElementsByClassName("person recentmessage")[0].textContent = sliceRecent(msg);
                    }
                }
            }
            return false;
        }

        function receiveMsg() {
            clearTimeout(window.receiveTimeout);
            var receivedMsgs = document.getElementsByClassName("message received");
            var timestamp = 0;
            var receiver = document.getElementById("personname").textContent;
            if (receivedMsgs.length != 0)
                var timestamp = receivedMsgs[receivedMsgs.length - 1].getAttribute("timestamp");
            // console.log(receivedMsgs, timestamp)
            var data = "sender=" + "<?php echo $_SESSION["username"] ?>" + "&receiver=" + receiver + "&prikey=" + window.prikey + "&timestamp=" + timestamp;
            // console.log(data);
            var httpRequest = new XMLHttpRequest;
            httpRequest.onreadystatechange = function(){
                if (httpRequest.readyState === 4) { // Request is done
                    if (httpRequest.status === 200) { // successfully
                        callback(httpRequest.responseText); // We're calling our method
                    }
                }
            };
            httpRequest.open('POST', "getNewMsgs.php");
            httpRequest.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            httpRequest.send(data);

            function callback(data) {
                // console.log(data);
                if(data.trim() == "") return false;
                var d = JSON.parse(data);
                var msgs = d["messages"];
                var chatmessages = document.getElementById("chatmessages");
                for(var i=0; i<msgs.length; ++i) {
                    newMsg = document.createElement("div");
                    newMsg.className = "message received";
                    newMsg.textContent = msgs[i][0];
                    newMsg.setAttribute("timestamp", msgs[i][1]);
                    chatmessages.appendChild(newMsg);
                    $("#chatmessages").children(":last-child").animate({ right: '0'}, 'fast');
                }
                chatmessages.scrollTop = chatmessages.scrollHeight;
                allchats = document.getElementsByClassName("personnamerecent");
                for(var i=0; i<allchats.length; ++i) {
                    if(allchats[i].getElementsByClassName("person name")[0].textContent == receiver && chatmessages.lastChild != null) {
                        allchats[i].getElementsByClassName("person recentmessage")[0].textContent = sliceRecent(chatmessages.lastChild.textContent);
                    }
                }
                window.receiveTimeout = setTimeout(receiveMsg, 1000);
            }
            return false;
        }
        window.receiveTimeout = setTimeout(receiveMsg, 1000);
        clearTimeout(window.receiveTimeout);
        var prikey = "", pubkey = "";
        var chats = {"Person 1": [[1, "Hi"], [2, "Hello"]],
            "Person 2": [[2, "Hello how are you?"], [1, "Hi, I am fine"], [1, "How are you?"]],
            "Rahul": [[1, "Hey"], [1, "What's up?"], [2, "Nothing Much"], [2, "How are you?"], [2, "Where are you?"], [2, "What are you?"], [1, "I'm good"], [1, "I'm in Bangalore"], [1, "I'm a human being"], [1, "Do you need any help?"], [2, "No, thanks for asking. What have you been up to?"], [1, "I have been working on creating a website"], [1, "That's nice"]]
        }
        // var chatObjs = document.getElementsByClassName("chat")
        // for(var i=0; i<chatObjs.length; ++i) {
        //     chatObjs[i].addEventListener("click", loadChat)
        // }
        function loadChat() {
            $("#chattop").animate({ left: '100%'}, 'fast');
            $("#messageinput").animate({ left: '100%'}, 'fast');
            $("#chatmessages").children(".sent").animate({ left: '100%'}, 'fast');
            $("#chatmessages").children(".received").animate({ right: '100%'}, 'fast');
            clearTimeout(window.receiveTimeout);
            if(!document.getElementById("chattop") || !document.getElementById("chatmessages") || !document.getElementById("messageinput")) {
                document.getElementById("rightcolumn").innerHTML = `
                <div id="chattop">
                    <img id="profpic" src="images/person.png">
                    <p id="personname">Person 2</p>

                </div>
                <div id="chatmessages">
                    
                </div>
            
                <form id="messageinput" method="POST" onsubmit="return addMsg()" autocomplete="off">
                    <input type="text" id="msginputbox" placeholder="Type a message..." autocomplete="off" maxlength="160">
                    <input type="submit" value="" id="msgsend">
                </form>
                `
            }
            $("#chattop").animate({ left: '0'}, 'slow');
            $("#messageinput").animate({ left: '0'}, 'slow');
            let name = this.getElementsByClassName("person name")[0].textContent;
            var recent = this.getElementsByClassName("person recentmessage")[0];
            // console.log(name);
            current_person_name = document.getElementById("personname");
            current_person_name.textContent = name;
            var httpRequest = new XMLHttpRequest;
            httpRequest.onreadystatechange = function(){
                if (httpRequest.readyState === 4) { // Request is done
                    if (httpRequest.status === 200) { // successfully
                        callback(httpRequest.responseText); // We're calling our method
                    }
                }
            };
            // console.log("getMsgs.php?" + "sender=" + "<?php echo $_SESSION["username"] ?>" + "&receiver=" + name)
            httpRequest.open('GET', "getMsgs.php?" + "sender=" + "<?php echo $_SESSION["username"] ?>" + "&receiver=" + name);
            httpRequest.send();

            function callback(data){
                console.log(data);
                d = JSON.parse(data);
                messages = d["messages"];
                window.prikey = d["prikey"];
                window.pubkey = d["pubkey"];
                // console.log(messages);
                chatmessages = document.getElementById("chatmessages");
                chatmessages.innerHTML = "";
                for(var i=0; i<messages.length; ++i) {
                    let message = document.createElement("div");
                    if(messages[i][0] == 1) {
                        message.className = "message sent";
                    }
                    else if(messages[i][0] == 2) {
                        message.className = "message received";
                    }
                    message.textContent = messages[i][1];
                    message.setAttribute("timestamp", messages[i][2]);
                    // console.log(message)
                    chatmessages.appendChild(message);
                }
                chatmessages.scrollTop = chatmessages.scrollHeight;
                document.getElementById("msginputbox").focus();
                if(messages.length > 0)
                    recent.textContent = sliceRecent(messages[messages.length - 1][1]);
                // console.log(messages[messages.length - 1][1]);
                // console.log(recent);
                // console.log($("#chatmessages").children(".message"));
                // console.log(document.getElementById("chatmessages").getElementsByClassName("sent"))
                // $(document).on('change','#myid', function(){
                $("#chatmessages").children(".sent").animate({ left: '0'}, 'slow');
                $("#chatmessages").children(".received").animate({ right: '0'}, 'slow');
                window.receiveTimeout = setTimeout(receiveMsg, 1000);
            }
        }

        function loadChats() {
            let left_column = document.getElementById("leftcolumn");
            for (var key of Object.keys(chats)) {
                console.log(key + " -> " + chats[key])
                let chat = document.createElement("button");
                chat.className = "chat";
                let img = document.createElement("img");
                img.className = "profpic";
                img.src = "images/person.png";
                chat.appendChild(img);
                let personnamerecent = document.createElement("div");
                personnamerecent.className = "personnamerecent";
                let personname = document.createElement("p");
                personname.className = "person name";
                personname.textContent = key;
                personnamerecent.appendChild(personname);
                let personrecent = document.createElement("p");
                personrecent.className = "person recentmessage";
                personrecent.textContent = sliceRecent(chats[key][chats[key].length-1][1]);
                personnamerecent.appendChild(personrecent);
                chat.addEventListener("click", loadChat);
                chat.appendChild(personnamerecent);
                left_column.appendChild(chat);
            }
        }
        // loadChats()
    </script>
    <?php
        // print_r($a->{'users'});
        // print_r($a->{'recentmsgs'}->{'Person 1'}[0]);
        // echo "<script> console.log($a) </script>";
        foreach($a->{'users'} as $user) {
            // var_dump($user);
            $t = $a->{'recentmsgs'}->{$user}[0];
            echo "<script> addchat('$user') </script>";
        }

        // if($_SERVER["REQUEST_METHOD"] == "POST"){
        //     if(empty(trim($_POST["username"]))){
        //         $username_err = "Please enter username.";
        //     } else{
        //         $username = trim($_POST["username"]);
        //     }

        //     $url = 'http://127.0.0.1:8000/getUserMsgs';
        //     $data = array('sender' => $_SESSION["username"], 'receiver' => $username);

        //     // use key 'http' even if you send the request to https://...
        //     $options = array(
        //         'http' => array(
        //             'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        //             'method'  => 'POST',
        //             'content' => http_build_query($data)
        //         )
        //     );
        //     $context  = stream_context_create($options);
        //     $result = file_get_contents($url, false, $context);
        //     if ($result === FALSE) { /* Handle error */ }
        //     $b = json_decode($result);
        //     $msgs = $b->{'messages'};
        //     echo "<script> loadChat($username, json_encode($msgs)) </script>";
        // }
    ?>
</body></html>