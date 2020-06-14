<?php
//require_once('./src/chessObjects/Pawn.php');
require_once('/Users/Fadeev/Downloads/vk/src/controllers/GameController.php');
//
//switch ($_SERVER['REQUEST_METHOD']) {
//    case 'POST':
//        GameController::startGame();
//        break;
//    case 'PUT':
//        echo "PUT";
//        $response = GameController::makeAMove($_POST['game_data']);
//        break;
//    case 'GET':
//        echo "GET";
//        $response = GameController::getStatistics();
//        break;
//    default:
////        exit('Not supported request');
//}

GameController::startGame();

for ($i = 1; $i < 10; $i++) {
    var_dump(GameController::makeAMove(trim(fgets(STDIN))));
}
$response = GameController::getStatistics();

echo $response;

//$class = new Pawn("Pawn", "white");
//
//echo $class->getName();
//echo $class->validateMove();