<?php
session_start();
require_once("voicerss_tts.php");
if (file_exists("globalvars.json")) {
    $globalvars = json_decode(file_get_contents("globalvars.json"),true);
}
else {
    $globalvars = array();
}
if (!isset($_SESSION["pairing_code"])) {
    while (True) {
        $pairing_code = random_int(100000, 999999);
        if (isset($globalvars["pairing_codes"])) {
            if (!in_array($pairing_code, $globalvars["pairing_codes"])) {
                array_push($globalvars["pairing_codes"], $pairing_code);
                $_SESSION["pairing_code"] = $pairing_code;
                break;
            }
        }
        else {
            $globalvars["pairing_codes"] = array();
            if (!in_array($pairing_code, $globalvars["pairing_codes"])) {
                array_push($globalvars["pairing_codes"], $pairing_code);
                $_SESSION["pairing_code"] = $pairing_code;
                break;
            }
        }
    }
}
else {
    $pairing_code = $_SESSION["pairing_code"];
}
    if(isset($globalvars[$pairing_code])) {
        $GLOBALS["data"] = $globalvars[$pairing_code];
        if($GLOBALS["data"]["team1score"]>=11) {
            if($GLOBALS["data"]["team2score"]+1!=$GLOBALS["data"]["team1score"]) {
                $GLOBALS["data"]["team1win"]=true;
            }
        }
        if($GLOBALS["data"]["team2score"]>=11) {
            if($GLOBALS["data"]["team1score"]+1!=$GLOBALS["data"]["team2score"]) {
                $GLOBALS["data"]["team2win"]=true;
            }
        }
        if(!isset($GLOBALS["data"]['team'])) {
            $GLOBALS["data"]["team"]=0;
        }
        if(!isset($_SESSION["servebit"])) {
            $_SESSION["servebit"]=1;
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($_POST['t1name']!="") {
                $GLOBALS["data"]["team1"] = $_POST["t1name"];
            }
            if ($_POST['t2name']!="") {
                $GLOBALS["data"]["team2"] = $_POST["t2name"];
            }
            if ($_POST['team1add']=="true") {
                $GLOBALS["data"]["team1score"]++;
                if ($_SESSION["servebit"]<=1) {
                    $_SESSION["servebit"]++;
                }
                else {
                    changeteam();
                    $_SESSION["servebit"]--;
                }
            }
            if ($_POST['team1remove']=="true") {
                if (!$GLOBALS["data"]["team1score"]==0) {
                    $GLOBALS["data"]["team1score"] = $GLOBALS["data"]["team1score"] - 1;
                    if ($_SESSION["servebit"]==0) {
                        $_SESSION["servebit"]++;
                    }
                    else {
                        changeteam();
                        $_SESSION["servebit"]--;
                    }
                }
            }
            if ($_POST['team2add']=="true") {
                $GLOBALS["data"]["team2score"] = $GLOBALS["data"]["team2score"] + 1;
                if ($_SESSION["servebit"]<=1) {
                    $_SESSION["servebit"]++;
                }
                else {
                    changeteam();
                    $_SESSION["servebit"]--;
                }
            }
            if ($_POST['team2remove']=="true") {
                if (!$GLOBALS["data"]["team2score"]==0) {
                    $GLOBALS["data"]["team2score"] = $GLOBALS["data"]["team2score"] - 1;
                    if ($_SESSION["servebit"]==0) {
                        $_SESSION["servebit"]++;
                    }
                    else {
                        changeteam();
                        $_SESSION["servebit"]--;
                    }
                }
            }
            if ($_POST["resetgame"]=="true") {
                $GLOBALS["data"]["team1"] = "Team 1";
                $GLOBALS["data"]["team2"] = "Team 2";
                $GLOBALS["data"]["team1score"] = 0;
                $GLOBALS["data"]["team2score"] = 0;
                $GLOBALS["data"]["team"] = 0;
                $_SESSION["servebit"]=0;
                $GLOBALS["data"]["team1win"] = false;
                $GLOBALS["data"]["team2win"] = false;
            }
            $roundprefix = "Round ";
            $emptyprefix = " ";
            $teamprefix = "team";
            $servingprefix = "serving.";
            $team2name = $GLOBALS["data"]["team2"];
            $to = "to";
            $team1scorestr = strval($GLOBALS["data"]["team1score"]);
            $team2scorestr = strval($GLOBALS["data"]["team2score"]);
            $currentroundnumber = strval($GLOBALS["data"]["team1score"] + $GLOBALS["data"]["team2score"] + 1);
            $currentservingname = $GLOBALS["data"][$teamprefix . strval($GLOBALS["data"]["team"]+1)];

            $tts = new VoiceRSS;
            $voice = $tts->speech([
                'key' => file_get_contents("voicerss_key.txt"),
                'hl' => 'en-au',
                'v' => 'Jack',
                'src' => $roundprefix . $emptyprefix . $currentroundnumber . $emptyprefix . $currentservingname . $emptyprefix .
                    $servingprefix . $emptyprefix . $team1scorestr . $emptyprefix . $team2scorestr . $to . $emptyprefix . $team2name,
                'r' => '0',
                'c' => 'mp3',
                'f' => '48khz_16bit_stereo',
                'ssml' => 'false',
                'b64' => 'true'
            ]);
            $GLOBALS["data"]["audiourl"] = $voice["response"];
            $globalvars[$pairing_code] = $GLOBALS["data"];
        }
        else {
            $GLOBALS["data"] = $globalvars[$pairing_code];
        }
    }
    else {
        $GLOBALS["data"]["team1"] = "Team 1";
        $GLOBALS["data"]["team2"] = "Team 2";
        $GLOBALS["data"]["team1score"] = 0;
        $GLOBALS["data"]["team2score"] = 0;
        $GLOBALS["data"]["team"] = 0;
        $GLOBALS["data"]["team1win"] = false;
        $GLOBALS["data"]["team2win"] = false;
    }

    function changeteam() {
        if ($GLOBALS["data"]["team"]==0) {
            $GLOBALS["data"]["team"]=1;
        }
        else {
            $GLOBALS["data"]["team"]=0;
        }
    }
    $globalvars[$pairing_code] = $GLOBALS["data"];
    file_put_contents("globalvars.json",json_encode($globalvars));
?>
<html>
    <head>
    </head>
    <body>
        <form id="assignmentform" action="server.php" method="post">
            <h4>Team 1 name: </h4><input onchange="submitform()" value="<?php echo $GLOBALS["data"]["team1"]?>" type="text" name="t1name"/>
            <h4>Team 2 name: </h4><input onchange="submitform()" value="<?php echo $GLOBALS["data"]["team2"]?>" type="text" name="t2name"/><br/><br/>
            <button onclick="team1addfn()">Add 1 point to <?php echo $GLOBALS["data"]["team1"]?></button>
            <input type="hidden" id="team1add" name="team1add" value=""/>
            <button onclick="team1removefn()">Take 1 point from <?php echo $GLOBALS["data"]["team1"]?></button>
            <input type="hidden" id="team1remove" name="team1remove" value=""/>
            <button onclick="team2addfn()">Add 1 point to <?php echo $GLOBALS["data"]["team2"]?></button>
            <input type="hidden" id="team2add" name="team2add" value=""/>
            <button onclick="team2removefn()">Take 1 point from <?php echo $GLOBALS["data"]["team2"]?></button>
            <input type="hidden" id="team2remove" name="team2remove" value=""/>
            <button onclick="resetgamefn()">Reset Game</button>
            <input type="hidden" id="resetgame" name="resetgame" value=""/>
        </form>
    <p>Pairing Code: <?php echo $pairing_code?></p>
    </body>
    <script>
        document.getElementById("team1add").value="";
        document.getElementById("team1remove").value="";
        document.getElementById("team2add").value="";
        document.getElementById("team2remove").value="";
        function team1addfn() {
            document.getElementById("team1add").value="true";
            document.getElementById("assignmentform").submit();
        }
        function team1removefn() {
            document.getElementById("team1remove").value="true";
            document.getElementById("assignmentform").submit();
        }
        function team2addfn() {
            document.getElementById("team2add").value="true";
            document.getElementById("assignmentform").submit();
        }
        function team2removefn() {
            document.getElementById("team2remove").value="true";
            document.getElementById("assignmentform").submit();
        }
        function submitform() {
            document.getElementById("assignmentform").submit();
        }
        function resetgamefn() {
            document.getElementById("resetgame").value="true";
            document.getElementById("assignmentform").submit();
        }
    </script>
</html>