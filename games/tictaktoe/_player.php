<?php

include_once '../../Database.php';

class _player {

    public static function init($user_id,$room_id) {
        if (!_player::isRoomFull($room_id)) {
            _player::addPlayer($user_id, $room_id);
        } else {
            echo "Room is full try another one ...";
        }
    }

    public static function isRoomFull($room_id) {
        $db = new Database();
        $db->connect();
        $result = $db->query("select max_players from rooms where id=" . $room_id);
        if ($data = mysql_fetch_array($result)) {
            $capacity = $data[0];
            $result2 = $db->query("select count(room_id) from player where room_id=" . $room_id);
            if ($data2 = mysql_fetch_array($result2)) {
                if ($data2[0] > $capacity) {
                    $db->close();
                    return true;
                } else {
                    $db->close();
                    return false;
                }
            } else {
                $db->close();
                return true;
            }
        } else {
            $db->close();
            return true;
        }
        $db->close();
    }

    public static function addPlayer($user_id, $room_id) {
        $db = new Database();
        $db->connect();

        if (_player::isExists($user_id, $room_id)) {
            _player::deletePlayer($user_id, $room_id);
            _player::addPlayer($user_id, $room_id);
        } else {
            // Decide Turn
            $res1 = $db->query("select turn from player where room_id=" . $room_id);
            if ($data1 = mysql_fetch_array($res1)) {
                // Second turn
                if($data1[0]==1)
                {
                    $db->query("insert into player (user_id,room_id,turn,score,p2p_data) values "
                        . "($user_id,$room_id,0,0,'0-0,0-0,0-0,0-0,0-0,0-0,0-0,0-0,0-0+0');");
                }else{
                    //first turn
                    $db->query("insert into player (user_id,room_id,turn,score,p2p_data) values "
                        . "($user_id,$room_id,1,0,'0-0,0-0,0-0,0-0,0-0,0-0,0-0,0-0,0-0+0');");
                }
            } else {
                // First turn
                $db->query("insert into player (user_id,room_id,turn,score,p2p_data) values "
                        . "($user_id,$room_id,1,0,'0-0,0-0,0-0,0-0,0-0,0-0,0-0,0-0,0-0+0');");
            }
        }
        $db->close();
    }

    public static function isExists($user_id, $room_id) {
        $db = new Database();
        $db->connect();
        $result = $db->query("select * from player where user_id=$user_id and room_id=$room_id");
        if (mysql_num_rows($result) > 0) {
            //exists
            $db->close();
            return true;
        } else {
            $db->close();
            return false;
        }
        $db->close();
    }

    public static function deletePlayer($user_id, $room_id) {
        $db = new Database();
        $db->connect();
        $db->query("delete from player where user_id=$user_id and room_id=$room_id");
        $db->close();
    }

}


function getGameState($room_id)
{
        $db = new Database();
        $db->connect();
        $result = $db->query("select max_players from rooms where id=" . $room_id);
        if ($data = mysql_fetch_array($result)) {
            $capacity = $data[0];
            $result2 = $db->query("select count(room_id) from player where room_id=" . $room_id);
            if ($data2 = mysql_fetch_array($result2)) {
                if ($data2[0] == $capacity) {
                    $db->close();
                    return true;
                }else{
                    $db->close();
                    return false;
                }
            }else{
                $db->close();
                return false;
            }
        }
        $db->close();
}

if(isset($_POST['req']))
{
    $req = $_POST['req'];
    
    if($req=="gameState")
    {
        $room_id = $_POST['room_id'];
        $db = new Database();
        $db->connect();
        $result = $db->query("select max_players from rooms where id=" . $room_id);
        if ($data = mysql_fetch_array($result)) {
            $capacity = $data[0];
            $result2 = $db->query("select count(room_id) from player where room_id=" . $room_id);
            if ($data2 = mysql_fetch_array($result2)) {
                if ($data2[0] == $capacity) {
                    echo true;
                }else{
                    echo false;
                }
            }else{
                echo false;
            }
        }
    }
    
    
    if($req=="myTurn")
    {
        $user_id = $_POST['user_id'];
        $room_id = $_POST['room_id'];
        
        $db = new Database();
        $db->connect();
        $result = $db->query("select turn from player where user_id=".$user_id." and room_id=".$room_id);
        if ($data = mysql_fetch_array($result)) {
            if($data[0]==1)
            {
                echo true;
            }else{
                echo false;
            }
        }else{
            echo false;
        }
    }
    
    
    if($req=="shuffleTurn")
    {
        $room_id = $_POST['room_id'];
        
        $db = new Database();
        $db->connect();
        $result = $db->query("select user_id,turn from player where room_id=".$room_id);
        while ($data = mysql_fetch_array($result)) {
            if($data[1]==1)
            {
                $db->query("update player set turn=0 where user_id=".$data[0]);
            }else{
                $db->query("update player set turn=1 where user_id=".$data[0]);
            }
        }
    }
    
    
    if($req=="p2p_data")
    {
        $room_id = $_POST['room_id'];
        $data = $_POST['data'];
        
        $db = new Database();
        $db->connect();
        $db->query("update player set p2p_data='$data' where room_id=".$room_id);
    }
    
    if($req=="loadData")
    {
        $room_id = $_POST['room_id'];
        
        $db = new Database();
        $db->connect();
        $result = $db->query("select p2p_data from player where room_id=".$room_id." limit 1");
        $data = mysql_fetch_array($result);
        echo $data[0];
    }
    
    
    if($req=="gamePlayers")
    {
        $room_id = $_POST['room_id'];
        $caption_done = 0;
        $db = new Database();
        $db->connect();
        $result = $db->query("select user_id,turn,score from player where room_id=".$room_id);
        while ($data = mysql_fetch_array($result)) {
            $res = $db->query("select display_name from auth_users where id=".$data[0]);
            $dat = mysql_fetch_array($res);
            
            if(!getGameState($room_id) && $caption_done==0)
            {
                echo "<div style='text-align: center;vertical-align: center;width: 100%;height: 30px; border: none; background-color: orange;'>"
                . "Waiting for players ..."
                        . "</div>";
                $caption_done = 1;
            }else if($caption_done==0){
                echo "<div style='text-align: center;vertical-align: center;width: 100%;height: 30px; border: none; background-color: lightblue;'>"
                . "Game Started"
                        . "</div>";
                $caption_done = 1;
            }
            
            if($data[1]==1)
            {
                echo "<div style='text-align: center;vertical-align: center;width: 100%;height: 30px; border: 1px solid black; background-color: lightgreen;'>"
                . "$dat[0] - $data[2]"
                        . "</div>";
            }else{
                echo "<div style='text-align: center;width: 100%;height: 30px; border: none; background-color: white;'>"
                . "$dat[0] - $data[2]"
                        . "</div>";
            }
        }
    }
}