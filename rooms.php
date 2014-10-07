<?php
session_start();

include_once 'Database.php';

if(!isset($_SESSION['user_name']))
{
    header("index.php");
}
if(isset($_GET['game_id']))
{
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
    $display_name = $_SESSION['display_name'];
    $game_id = $_GET['game_id'];
    $game_title = "";
    $game_link = "";
    
    $db = new Database();
    $db->connect();
    
    // If user id in room, delete it from every game cuz he left game and come to rooms
    $db->query("delete from player where user_id=$user_id");

    $result = $db->query("select game_title,game_link from  games where id=".$game_id);
    if($data = mysql_fetch_array($result))
    {
        $game_title = $data[0];
        $game_link = $data[1];
    }else{
        $db->close();
        echo "Selected Game Doesn't exists ...";
        exit();
    }
    $db->close();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>gameGTX Online - Portal</title>
        <link href="run.css" rel="stylesheet"/>
        <script src="jquery.js" type="text/javascript"></script>
        <script>
        
        $(document).ready(function(){
            $.post("game.php",{'req':'getRoomsList','game_id':'<?php echo $game_id; ?>'},
            function(ret)
            {
                $("#rooms").html(ret);
            });
        });
        
        function goto_game(room_id)
        {
            var glink = $("#glink").val();
            window.location = glink + "?room_id=" + room_id;
        }
        
        </script>
    </head>
    <body>
        <input type='hidden' id='glink' value='<?php echo $game_link; ?>'/>
        <div class="bg_green" style="width: 100%; height: 60px;color: white;">
            <h3 style="float: left; margin-left: 10px; margin-top: 20px;">gameGTX Online - Rooms for <?php echo $game_title; ?> <a style='color: lightblue;' href='index.php?username=<?php echo $user_name; ?>'> << BACK</a></h3>
            <b style="float: right;margin-right: 10px; margin-top: 20px;"> Welcome, <?php echo $_SESSION['user_name']; ?></b>
        </div>      
        <div id="rooms" class='Lgreen' style="width: 100%;min-height: 400px;">
            
        </div>
        <div class="bg_green" style="width: 100%; height: 60px;color: white;">
            <center><p style='margin-top: 20px;'>All Rights Reserved. Copyright (c) DemiXsoft 2015</p></center>
        </div>
</body>
</html>
<?php
}else{
    echo "No Game selected!";
}
?>