<?php
session_start();

include_once '_player.php';

if(!isset($_SESSION['user_id']))
{
    header("Location: index.php");
}

if(isset($_GET['room_id']))
{
    
    $user_id = $_SESSION['user_id'];
    $room_id = $_GET['room_id'];
    
    $p = new _player();
    
    $p->init($user_id, $room_id);
?>
<html>
    <head>
        <title>TicTacToe</title>
        <link href="run.css" rel="stylesheet"/>
        <script src="jquery.js" type="text/javascript"></script>
        <script src="run.js" type="text/javascript"></script>
        
    </head>
    <body>
        <input id='user_id' type='hidden' value='<?php echo $user_id; ?>'/>
        <input id='room_id' type='hidden' value='<?php echo $room_id; ?>'/>
        
        <div id="screen" style='float: left;'>
            
        </div>
        <div style='float: left;margin-left: 10px;width:200px;height:200px;border: 2px solid black;'  id='players'>
            
        </div>
    </body>
</html>

<?php

}else{
    echo "No Room Selected!";
}

?>