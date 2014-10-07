<?php

include_once 'Database.php';

$db = new Database();

if(isset($_POST['req']))
{
    $req = $_POST['req'];
    
    // Get Games
    if($req=="getGamesList")
    {
        $db->connect();
        $result = $db->query("select * from games");
        while($data=  mysql_fetch_array($result))
        {
            echo "<div class='off_white' style='width: 200px; height: 300px;float: left;margin-left: 5px; margin-top: 5px;'>";
            echo "<div class='bg_blue' style='float: left;height: 30px;width: 100%;'><b style='color: white;margin-left: 5px;margin-top: 5px;'> ".$data['game_title']."</b></div>"; // game title
            echo "<img height='200' width='200' style='float: left;' style='width:200px;height:200px;' src='".$data['game_link']."img/main.png'/>"; // main image            
            echo "<input style='float: right;margin-right: 5px;margin-top: 10px;' onclick='goto_room($data[0])' type='button' value='Play Now'/>"; // Rooms Button            
            echo "<div style='float: left;margin-left: 5px;margin-top: 10px;'> ".$data['game_desc']." </div>"; // game title            
            echo "</div>";
        }
        $db->close();
    }
    
    // Get Rooms
    if($req=="getRoomsList")
    {
        $game_id = $_POST['game_id'];
        $db->connect();
        $result = $db->query("select * from rooms where game_id=".$game_id);
        while($data=  mysql_fetch_array($result))
        {
            
            $res_room = $db->query("select count(*) from player where room_id=".$data[0]);
            $data_room = mysql_fetch_array($res_room);
            
            $bttn_text = "Start Game";
            
            if($data_room[0]>0)
            {
                $bttn_text = "Join Game";
            }
            
            if($data_room[0] == $data['max_players'])
            {
                $bttn_text = "Room Full";
            }
            
            echo "<div class='off_white' style='width: 200px; height: 300px;float: left;margin-left: 5px; margin-top: 5px;'>";
            echo "<div class='bg_blue' style='float: left;height: 30px;width: 100%;'><b style='color: white;margin-left: 5px;margin-top: 5px;'> ".$data['room_name']."</b></div>"; // game title        
            echo "<input style='float: right;margin-right: 5px;margin-top: 10px;' type='button' onclick='goto_game($data[0])' value='$bttn_text'/>"; // Rooms Button            
            echo "<div style='float: left;margin-left: 5px;margin-top: 10px;'> ".$data_room[0]."/".$data['max_players']." </div>"; // game title            
            echo "</div>";
        }
        $db->close();
    }
}

