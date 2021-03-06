<?php
session_start();
$globalvars = array();
if (file_exists("globalvars.json")) {
    $globalvars = json_decode(file_get_contents("globalvars.json"),true);
}
else {
    $pairing_code_invalid = true;
}
if(isset($_POST["pairing_code"])) {
    if (isset($globalvars["pairing_codes"])) {
        if (in_array($_POST["pairing_code"], $globalvars["pairing_codes"])) {
            $pairing_code = $_POST["pairing_code"];
            $_SESSION["pairing_code"] = $pairing_code;
        } else {
            $pairing_code_invalid = true;
        }
    }
    else {
        $pairing_code_invalid = true;
    }
}
if(!isset($_SESSION["pairing_code"])) {
    //Set random overlay values so that program will not crash
    $data["team1"] = "Team 1";
    $data["team2"] = "Team 2";
    $data["team1score"] = 0;
    $data["team2score"] = 0;
    $data["team"] = 0;
    $data["team1win"] = false;
    $data["team2win"] = false;
}
else {
    $pairing_code = $_SESSION["pairing_code"];
    $data = $globalvars[$pairing_code];
}
?>
<?php if (!isset($_GET["ajax"])) {
    ?>
<!DOCTYPE HTML>
<head>
    <title>PongScoreboard</title>
    <meta name="apple-mobile-web-app-title" content="PongScoreboard">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');
        body {
            font-family: Roboto,sans-serif;
            margin: 0;
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
            height: 200%;
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
        html,body,#scorediv>* {
            min-height: 100vh;
        }
        #roundnumber {
            z-index: 1;
            padding: 0;
            margin: 0;
        }
        #roundsummary {
            text-align: center;
            transform:translateX(50%);
            position: fixed;
            width: 50%;
            z-index: 2;
            background-color: white;
            padding: 0;
        }
        <?php if(!isset($pairing_code)) {?>
        #scorediv {
            filter: blur(4px);
        }
        #pairing_code {
            text-align: center;
        }
        #pairingcode_input {
            text-align: center;
            transform:translate(50%,50%);
            position: fixed;
            width: 50%;
            z-index: 200;
            background-color: white;
        }
        #winnermodal {
            text-align: center;
            transform:translate(50%,50%);
            position: fixed;
            width: 50%;
            z-index: 200;
            background-color: white;
        }
        #pairingcode_label {
            margin-bottom: 50px;
            font-size:32px;
            padding-top: 0;
            color: black;
            text-align: center;
        }
        #error {
            color: red;
        }
        form > input,button {
            padding-top: 5px;
            padding-bottom: 5px;
            padding-left: 20px;
            padding-right: 20px;
            border: 0.5px solid black;
        }
        #roundsummary {
            filter:blur(4px);
        }

        form > input {
            margin-bottom: 50px;
        }
        <?php }?>

    </style>
</head>
<body>
<?php if (!isset($pairing_code)) {?>
    <div id="pairingcode_input">
        <form action="client.php" method="post">
            <?php
            if(isset($pairing_code_invalid)) {
                echo '<h3 id="error">Pairing Code not Found</h3>';
            }
            ?>
            <h1 id="pairingcode_label">Pairing Code:</h1>
            <input type="text" id="pairing_code" name="pairing_code"></input>
            <button type="submit">Go</button>
        </form>
    </div>


    <?php }?>
<?php if($data["team1win"] or $data["team2win"]) {?>

    <div id="winnermodal">
        <h1>A player has won the game!</h1>
        <p><?php if($data["team1win"]) {
            echo($data["team1"]);
        }
        else {
            echo($data["team2"]);
        }?> has won the game with <?php if($data["team1win"]) {
                echo($data["team1score"]);
            }
            else {
                echo($data["team2score"]);
            }?> points.</p>
    </div>
<?php }?>

<?php }
else {
    echo '<audio id="announcement" src="' . $data["audiourl"] . '" autoplay="autoplay"></audio>';
    ?>
        <div id="round_details">
        <div id="roundsummary">
            <h1 id="roundnumber">Round <?php echo $data["team1score"] + $data["team2score"] + 1?></h1>
            <h2><?php echo $data["team" . strval($data["team"]+1)]?> Serving</h2>
        </div>
        <div id="scorediv">
            <div id="team1">
                <h1><?php echo $data["team1"]?></h1>
                <h4><?php echo $data["team1score"]?></h4>
            </div>
            <div id="team2">
                <h1><?php echo $data["team2"]?></h1>
                <h4><?php echo $data["team2score"]?></h4>
            </div>
        </div>
        </div>
        <?php }?>
<?php if(!isset($_GET["ajax"])) {?>
<div id="round_details">
    <div id="roundsummary">
        <h1>Round <?php echo $data["team1score"] + $data["team2score"] + 1?></h1>
        <h2><?php echo $data["team"]?> Serving</h2>
    </div>
    <div id="scorediv">
        <div id="team1">
            <h1><?php echo $data["team1"]?></h1>
            <h4><?php echo $data["team1score"]?></h4>
        </div>
        <div id="team2">
            <h1><?php echo $data["team2"]?></h1>
            <h4><?php echo $data["team2score"]?></h4>
        </div>
    </div>
</div>
<script>
    function refreshScoreboard() {
        if (document.getElementById("round_details")!=undefined) {
            if (document.getElementById("round_details").innerHTML != xhttp.response.split("\r").join("")) {
                document.getElementById("round_details").innerHTML = xhttp.response.split("\r").join("");
            }
        }
    }
    var reloadScore = function () {
        xhttp = new XMLHttpRequest();
        xhttp.open("GET","/client.php?ajax=true");
        xhttp.send();
        xhttp.addEventListener("load", refreshScoreboard);
    };
    setInterval(reloadScore, 100);
</script>
</body>
</html>
<?php }?>
