<?php
if(file_exists("data.txt")) {
    $data = json_decode(file_get_contents("data.txt"),true);
}
else {
    $data["team1"] = "Team 1";
    $data["team2"] = "Team 2";
    $data["team1score"] = 0;
    $data["team2score"] = 0;
    file_put_contents("data.txt",json_encode($data));
}
?>
<html>
<head>
    <meta name="apple-mobile-web-app-title" content="PongScoreboard">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <script>
        function refreshScoreboard() {
            if (document.body.innerHTML==xhttp.response) {
                //Do nothing
            }
            else {
                document.body.innerHTML = xhttp.response;
            }
        }
        var reloadScore = function () {
            xhttp = new XMLHttpRequest();
            xhttp.open("GET","/client.php");
            xhttp.send();
            xhttp.addEventListener("load", refreshScoreboard);
        };
        setInterval(reloadScore, 100);
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
        body {
            font-family: Roboto,sans-serif;
            margin: -2px;
        }
        h1 {
            padding-top: 50%;
            font-size: 64px;
        }
        h4 {
            font-size: 34px;
        }
        #team1,#team2 {
            text-align: center;
            height: 100%;
            width: 50%;
            color: white;
        }
        #team1 {
            float: left;
            background-color: green;
        }
        #team2 {
            float: right;
            background-color: red;
        }
    </style>
</head>
<body>
    <div id="scorediv">
        <div id="team1">
            <h1><?php echo $data["team1"]?></h1>
            <h4><?php echo $data["team1score"]?></h4>
        </div>
        <div id="team2">
            <h1><?php echo $data["team2"]?></h1>
            <h4><?php echo $data["team2score"]?></h4>
        </div>
</body>
</html>
