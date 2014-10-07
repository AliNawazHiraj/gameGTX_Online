<?php
session_start();

include_once '_auth.php';
$auth = new _auth();

if(isset($_GET['username']))
{
    // Init Engine
    $user_name = $_GET['username'];
    $display_name = $user_name;
    if(isset($_GET['display_name']))
    {
        $display_name = $_GET['display_name'];
    }
    
    // Setting Up User
    if($auth->isExists($user_name))
    {
        $_SESSION['user_id'] = $auth->getUserID($user_name);
        $_SESSION['user_name'] = $user_name;
        $_SESSION['display_name'] = $display_name;        
    }else{
        // New user - Let's add up tp system
        $_SESSION['user_id'] = $auth->addUser($user_name, $display_name);
        $_SESSION['user_name'] = $user_name;
        $_SESSION['display_name'] = $display_name;   
    }

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
            $.post("game.php",{'req':'getGamesList'},
            function(ret)
            {
                $("#games").html(ret);
            });
        });
        
        function goto_room(game_id)
        {
            window.location = "rooms.php?game_id=" + game_id;
        }
        
        </script>
    </head>
    <body>
        <div class="bg_green" style="width: 100%; height: 60px;color: white;">
            <h3 style="float: left; margin-left: 10px; margin-top: 20px;">gameGTX Online</h3>
            <b style="float: right;margin-right: 10px; margin-top: 20px;"> Welcome, <?php echo $_SESSION['user_name']; ?></b>
        </div>      
        <div id="games" class='Lgreen' style="width: 100%;min-height: 400px;">
            
        </div>
        <div class="bg_green" style="width: 100%; height: 60px;color: white;">
            <center><p style='margin-top: 20px;'>All Rights Reserved. Copyright (c) DemiXsoft 2015</p></center>
        </div>
</body>
</html>

<?php
}else{
    echo "No Username Provided!";
}
?>