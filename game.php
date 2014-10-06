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
            echo "<tr><td><img style='width:200px;height:200px;' src='".$data['game_link']."img/main.png'/></td></tr>"; // main image
            echo "<tr><td><b>Title. </b> ".$data['game_title']." </td>"; // game title
            echo "<td><input type='button' value='Play Now'/></td></tr>"; // Rooms Button            
            echo "<tr><td><b>Description. </b> ".$data['game_desc']." </td></tr>"; // game title            
        }
        $db->close();
    }
}

