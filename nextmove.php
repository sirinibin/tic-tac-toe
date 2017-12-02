<?php
require_once("Maker.php");
require_once("Validator.php");
require_once("WinnerVerifier.php");


$boardState = $_POST['board'];
$playerUnit = $_POST['playerUnit'];

// X -> user, O=>bot

$v = new Validator;
$v->validate($boardState, $playerUnit);
$wv = new WinnerVerifier($v);

$winner = $wv->verifyPosition($boardState, $playerUnit);

header('Content-Type: application/json ');

if (isset($winner[3])) {


    $winnerUnit = $winner[3];
    unset($winner[3]);

    $response = [
        'game_over' => true,
        'winner' => $winnerUnit,
        'positions' => $winner
    ];
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
} else if ($playerUnit == 'O') {

    $response = [
        'game_over' => false,
        'data' => $winner
    ];
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
} else {

    $maker = new Maker($v, $wv);
    $move = $maker->makeMove($boardState, $playerUnit);


    if ($move) {
        $response = [
            'game_over' => false,
            'nextmove' => $move,
            'player' => 'O'
        ];
    } else {
        //game drawn
        $response = [
            'game_over' => true,
            'drawn' => true,
        ];
    }


    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;

}




?>