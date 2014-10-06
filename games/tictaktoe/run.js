
$(document).ready(function () {

    // Setting Up Screen
    $("#screen").css("width", "600");
    $("#screen").css("height", "600");
    $("#screen").css("border", "2px solid black");

    //varables
    var block_width = (600 / 3) - 2;
    var block_height = (600 / 3) - 2;
    var block_left = 12;
    var block_top = 12;

    // Generating blocks
    for (var i = 1; i <= 9; i++)
    {

        var block = "<div id='block" + i + "' class='block' style='position: absolute; left: " + block_left + "px;top: " + block_top + "px;border: 1px solid blue;float: left;width: " + block_width + "px;height: " + block_height + "px;'></div>";
        $("#screen").append(block);

        block_left = parseInt(block_left, 10) + parseInt(block_width, 10);
        if (block_left > 480)
        {
            block_left = 12;
            block_top = block_top + parseInt(block_height, 10);
        }

    }
    
    // 

});
