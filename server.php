<?php
    $data=[];
    if(file_exists("data.txt")) {
        $data = json_decode(file_get_contents("data.txt"),true);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($_POST['t1name']!="") {
                $data["team1"] = $_POST["t1name"];
            }
            if ($_POST['t2name']!="") {
                $data["team2"] = $_POST["t2name"];
            }
            if ($_POST['team1add']=="true") {
                $data["team1score"]++;
            }
            if ($_POST['team1remove']=="true") {
                if ($data["team1score"]==0) {
                    //Do nothing
                }
                else {
                    $data["team1score"] = $data["team1score"] - 1;
                }
            }
            if ($_POST['team2add']=="true") {
                $data["team2score"] = $data["team2score"] + 1;
            }
            if ($_POST['team2remove']=="true") {
                if ($data["team2score"]==0) {
                    //Do nothing
                }
                else {
                    $data["team2score"] = $data["team2score"] - 1;
                }
            }
            file_put_contents("data.txt", json_encode($data));
        }
        else {
            $data = json_decode(file_get_contents("data.txt"),true);
        }
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

        </script>
    </head>
    <body>
        <form id="assignmentform" action="server.php" method="post">
            <h4>Team 1 name: </h4><input onchange="submitform()" value="<?php echo $data["team1"]?>" type="text" name="t1name"/>
            <h4>Team 2 name: </h4><input onchange="submitform()" value="<?php echo $data["team2"]?>" type="text" name="t2name"/><br/><br/>
            <button onclick="team1addfn()">Add 1 point to <?php echo $data["team1"]?></button>
            <input type="hidden" id="team1add" name="team1add" value=""/>
            <button onclick="team1removefn()">Take 1 point from <?php echo $data["team1"]?></button>
            <input type="hidden" id="team1remove" name="team1remove" value=""/>
            <button onclick="team2addfn()">Add 1 point to <?php echo $data["team2"]?></button>
            <input type="hidden" id="team2add" name="team2add" value=""/>
            <button onclick="team2removefn()">Take 1 point from <?php echo $data["team2"]?></button>
            <input type="hidden" id="team2remove" name="team2remove" value=""/>
        </form>
    </body>
</html>