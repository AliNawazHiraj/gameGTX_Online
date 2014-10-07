
var item = null;

var img = 0; //tick
var play = false; // game _state
var my_turn = false; // you are playing
var p2p_data = "0-0,0-0,0-0,0-0,0-0,0-0,0-0,0-0,0-0+0"; // game play data 0-0 = block_id-user_id+img...

$(document).ready(function () {

    // Setting Up Screen
    $("#screen").css("width", "600");
    $("#screen").css("height", "600");
    $("#screen").css("border", "2px solid black");
    $("#win").hide();
    
    //varables
    var block_width = (600 / 3) - 2;
    var block_height = (600 / 3) - 2;
    var block_left = 13;
    var block_top = 18;

    // Generating blocks
    for (var i = 1; i <= 9; i++)
    {

        var block = "<img id='block" + i + "' src='img/q.png' style='position: absolute; left: " + block_left + "px;top: " + block_top + "px;border: none;float: left;width: " + block_width + "px;height: " + block_height + "px;'></img>";
        $("#screen").append(block);

        block_left = parseInt(block_left, 10) + parseInt(block_width, 10);
        if (block_left > 480)
        {
            block_left = 13;
            block_top = block_top + parseInt(block_height, 10);
        }

    }

    // Adding events of hover
    for (var i = 1; i <= 9; i++)
    {
        eval('$("#block' + i + '").mouseover(function (){$("#block' + i + '").css("border","1px solid grey");}).mouseout(function (){$("#block' + i + '").css("border","none");});');
        eval('$("#block' + i + '").click(function(){ block_click(' + i + ',getUserId(),getRoomId()); });');
    }

    setInterval(function () {
        getPlayers(getRoomId());
        load_data(getRoomId());
    }, 1000);
// end of doc ready
});

function isWinner()
{
    //win state checks START
    var temp_a1 = p2p_data.split("+");
    var temp_a2 = temp_a1[0].split(",");

    // conveting user-wise
    var u_arr = {};
    for (var i = 0; i < temp_a2.length; i++)
    {
        var temp_a3 = temp_a2[i].split("-");
        u_arr[i] = temp_a3[1];
    }

    // For current user
    var win = false;
    // LEFTS - RIGHTS
    if (u_arr[0] === getUserId() && u_arr[1] === getUserId() && u_arr[2] === getUserId())
        win = true;
    if (u_arr[3] === getUserId() && u_arr[4] === getUserId() && u_arr[5] === getUserId())
        win = true;
    if (u_arr[6] === getUserId() && u_arr[7] === getUserId() && u_arr[8] === getUserId())
        win = true;
    // TOPS - DOWNS
    if (u_arr[0] === getUserId() && u_arr[3] === getUserId() && u_arr[6] === getUserId())
        win = true;
    if (u_arr[1] === getUserId() && u_arr[4] === getUserId() && u_arr[7] === getUserId())
        win = true;
    if (u_arr[2] === getUserId() && u_arr[5] === getUserId() && u_arr[8] === getUserId())
        win = true;
    // DIAGONALS
    if (u_arr[0] === getUserId() && u_arr[4] === getUserId() && u_arr[8] === getUserId())
        win = true;
    if (u_arr[2] === getUserId() && u_arr[4] === getUserId() && u_arr[6] === getUserId())
        win = true;

    //win state checks STOP
    return win;
}

function block_click(block_id, user_id, room_id)
{
    $.post("_player.php", {'req': 'gameState', 'room_id': room_id},
    function (ret) {
        if (ret)
        {
            $.post("_player.php", {'req': 'myTurn', 'user_id': user_id, 'room_id': room_id},
            function (ret) {
                if (ret)
                {
                    // check if block already cliked
                    //alert(getDataTick(block_id));
                    if (getDataTick(block_id) === '0')
                    {


                        if (img === 0)
                        {
                            $("#block" + block_id).attr("src", "img/o.png");
                            setData(block_id, 1, user_id, 0);
                            shuffleTurn(room_id);
                        } else {
                            $("#block" + block_id).attr("src", "img/x.png");
                            setData(block_id, 2, user_id, 1);
                            shuffleTurn(room_id);
                        }

                        // Winner Check
                        if(isWinner())
                        {
                            setDataWin(getUserName() + " has Won the Game!");
                        }


                        //uploading p2p_data
                        $.post("_player.php", {'req': 'p2p_data', 'data': p2p_data, 'room_id': room_id},
                        function (ret) {

                        });

                    }
                }
            });
        }
    });
}

function getRoomId()
{
    return $("#room_id").val();
}

function getUserId()
{
    return $("#user_id").val();
}
function getUserName()
{
    return $("#user_name").val();
}

function getGameId()
{
    return $("#game_id").val();
}


function getPlayers(room_id)
{
    $.post("_player.php", {'req': 'gamePlayers', 'room_id': room_id},
    function (ret) {
        $("#players").html(ret);
    });
}


function getGameState(room_id)
{
    $.post("_player.php", {'req': 'gameState', 'room_id': room_id},
    function (ret) {
        return ret;
    });
}

function isMyTurn(user_id, room_id)
{
    $.post("_player.php", {'req': 'myTurn', 'user_id': user_id, 'room_id': room_id},
    function (ret) {
        return ret;
    });
}

function shuffleTurn(room_id)
{
    $.post("_player.php", {'req': 'shuffleTurn', 'room_id': room_id},
    function (ret) {
        //if (img === 0) {
        //img = 1
        //} else {
        //img = 0
        //}
        return true;
    });
}


function load_data(room_id)
{
    $.post("_player.php", {'req': 'loadData', 'room_id': room_id},
    function (ret) {
        p2p_data = ret;
        reload_board();
    });
}

function reload_board()
{
    //varables
    var block_width = (600 / 3) - 2;
    var block_height = (600 / 3) - 2;
    var block_left = 13;
    var block_top = 18;

    $("#screen").html("");
    var arr_img = p2p_data.split("+");

    if (arr_img[1] === '0' || arr_img[1] === '1')
    {

        var arr = arr_img[0].split(",");

        for (var j = 0; j < arr.length; j++)
        {
            var i = j + parseInt(1, 10);
            var arr2 = arr[j].split("-");
            if (arr2[0] === '0')
            {
                var block = "<img id='block" + i + "' src='img/q.png' style='position: absolute; left: " + block_left + "px;top: " + block_top + "px;border: none;float: left;width: " + block_width + "px;height: " + block_height + "px;'></img>";
                $("#screen").append(block);
            }
            if (arr2[0] === '1')
            {
                var block = "<img id='block" + i + "' src='img/o.png' style='position: absolute; left: " + block_left + "px;top: " + block_top + "px;border: none;float: left;width: " + block_width + "px;height: " + block_height + "px;'></img>";
                $("#screen").append(block);
            }
            if (arr2[0] === '2')
            {
                var block = "<img id='block" + i + "' src='img/x.png' style='position: absolute; left: " + block_left + "px;top: " + block_top + "px;border: none;float: left;width: " + block_width + "px;height: " + block_height + "px;'></img>";
                $("#screen").append(block);
            }

            block_left = parseInt(block_left, 10) + parseInt(block_width, 10);
            if (block_left > 480)
            {
                block_left = 13;
                block_top = block_top + parseInt(block_height, 10);
            }

        }

        // Adding events of hover
        for (var i = 1; i <= 9; i++)
        {
            eval('$("#block' + i + '").mouseover(function (){$("#block' + i + '").css("border","1px solid grey");}).mouseout(function (){$("#block' + i + '").css("border","none");});');
            eval('$("#block' + i + '").click(function(){ block_click(' + i + ',getUserId(),getRoomId()); });');
        }


        // setting up img
        if (arr_img[1] === '0')
        {
            img = 1;
        } else {
            img = 0;
        }


    } else {
        //User WON!
        $("#win").html("<center><h3 style='color: white;margin-top: 20px;'>" + arr_img[1] + "</h3><p><a href='../../rooms.php?game_id=" + getGameId() + "'>Goto Rooms >></a></p></center>");
        $("#win").show();
    }

    //---
}

function setData(block_id, tick, user_id, img)
{
    var arr_img = p2p_data.split("+");

    var arr = arr_img[0].split(",");
    var temp_data = "";
    for (var j = 0; j < arr.length; j++)
    {
        var i = j + parseInt(1, 10);
        if (i === block_id)
        {
            temp_data = temp_data + tick + "-" + user_id;
        } else {
            var arr2 = arr[j].split("-");
            temp_data = temp_data + arr2[0] + "-" + arr2[1];
        }

        if (j < arr.length - 1)
        {
            temp_data = temp_data + ",";
        }
    }

    temp_data = temp_data + "+" + img;

    p2p_data = temp_data;
}



function setDataWin(img)
{
    var arr_img = p2p_data.split("+");

    var arr = arr_img[0].split(",");
    var temp_data = "";
    for (var j = 0; j < arr.length; j++)
    {
        var i = j + parseInt(1, 10);
        var arr2 = arr[j].split("-");
        temp_data = temp_data + arr2[0] + "-" + arr2[1];

        if (j < arr.length - 1)
        {
            temp_data = temp_data + ",";
        }
    }

    temp_data = temp_data + "+" + img;

    p2p_data = temp_data;
}

function getDataTick(block_id)
{
    var arr = p2p_data.split(",");
    for (var j = 0; j < arr.length; j++)
    {
        var i = j + parseInt(1, 10);
        if (i === block_id)
        {
            var arr2 = arr[j].split("-");
            return arr2[0];
        }
    }
}