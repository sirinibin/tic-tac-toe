$boardState = [
    [
        "",
        "",
        ""
    ],
    [
        "",
        "",
        ""
    ],
    [
        "",
        "",
        ""
    ]
];
$game_over=false;

$(document).ready(function () {

    updateBoard();

    console.log($boardState);


});
function markCell($id, $i, $j, $playerUnit) {


    if ($boardState[$i][$j]||$game_over) {
        return;
    }

    $('#' + $id).html($playerUnit);
    // $("#"+$id).disable();
    // $("#"+$id).unbind("onclick");

    $boardState[$i][$j] = $playerUnit;

    console.log($boardState);

    getNextMove($playerUnit);

}
function updateBoard() {

    $table = "<table border=\"1\" cellpadding=\"20\">";

    for ($i = 0; $i < 3; $i++) {

        $table += "<tr>";

        for ($j = 0; $j < 3; $j++) {

            $table += "<td id='cell_" + $i + "_" + $j + "' style='cursor: pointer;' onclick='markCell(this.id," + $i + "," + $j + ",\"X\")'>" + $boardState[$i][$j] + "</td>";
        }
        $table += "</tr>";
    }
    $table += "</table>";

    $("#board").html($table);
}
function getNextMove($playerUnit) {


    $.post("/nextmove",
        {
            board: $boardState,
            playerUnit: $playerUnit
        },
        function (data, status) {

            if (data.game_over) {

                if (data.drawn) {
                    console.log("Game drawn");
                    $("#result").html("RESULT:Game drawn <button  onclick='restart();' >RESTART</button>");
                    $game_over=true;
                } else {
                    $("#result").html("RESULT:'" + data.winner + "' Won the game <button  onclick='restart();' >RESTART</button> ");
                    // console.log("Winnder:" + data.winner);
                    // console.log("Positions:" + data.positions);
                    markWinPositions(data);
                    $game_over=true;

                }


            } else if (data.nextmove) {

                // console.log("Next move:" + data.nextmove);
                //console.log("Player:" + data.player);

                $id = "cell_" + data.nextmove[1] + "_" + data.nextmove[0];

                markCell($id, data.nextmove[1], data.nextmove[0], data.player);

                // $("#"+$id).disable();

            }
        });


}
function markWinPositions(data) {

    $id1 = "cell_" + data.positions[0][1] + "_" + data.positions[0][0];
    $id2 = "cell_" + data.positions[1][1] + "_" + data.positions[1][0];
    $id3 = "cell_" + data.positions[2][1] + "_" + data.positions[2][0];

    $("#" + $id1).css("background-color", "green");
    $("#" + $id2).css("background-color", "green");
    $("#" + $id3).css("background-color", "green");
}
function restart() {

    $boardState = [
        [
            "",
            "",
            ""
        ],
        [
            "",
            "",
            ""
        ],
        [
            "",
            "",
            ""
        ]
    ];

    $game_over=false;
    updateBoard();
    $("#result").html('');
}