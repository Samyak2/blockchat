function getKeys(sender) {
    var httpRequest = new XMLHttpRequest;
    httpRequest.onreadystatechange = function(){
        if (httpRequest.readyState === 4) { // Request is done
            if (httpRequest.status === 200) { // successfully
                callback(httpRequest.responseText); // We're calling our method
            }
        }
    };
    httpRequest.open('GET', "getKeys.php?" + "sender=" + sender);
    httpRequest.send();

    function callback(data) {
        var names_keys = JSON.parse(data);
        var table = document.getElementById("keytable");
        var receiver = "";
        for(var i=0; i<names_keys.length; ++i) {
            if(names_keys[i][0] == sender) {
                var receiver = names_keys[i][1];
            }
            else {
                var receiver = names_keys[i][0];
            }
            row = document.createElement("tr");

            var name = document.createElement("td");
            name.textContent = receiver;
            row.appendChild(name);

            pubkey = document.createElement("td");
            pubkey.innerHTML = "<div class='key'>" + names_keys[i][2] + "</div>";
            row.appendChild(pubkey);

            prikey = document.createElement("td");
            prikey.innerHTML = "<div class='key'>" + names_keys[i][3] + "</div>";
            row.appendChild(prikey);

            table.appendChild(row);
        }
    }
}