<?php

include_once '../../Database.php';

class _player {

    public static function init($room_id) {
        if (!_player::isRoomFull($room_id)) {
            
        } else {
            echo "Room is full try another one ...";
        }
    }

    public static function isRoomFull($room_id) {
        $db = new Database();
        $db->connect();
        $result = $db->query("select max_players from rooms where id=" . $room_id);
        if ($data = mysql_fetch_array($result)) {
            $capacity = $dataq[0];
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
                $db->query("insert into player (user_id,room_id,turn,score) values "
                        . "($user_id,$room_id,0,0);");
            } else {
                // First turn
                $db->query("insert into player (user_id,room_id,turn,score) values "
                        . "($user_id,$room_id,1,0);");
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
