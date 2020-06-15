<?php

require_once __DIR__ . '/controllers/GameController.php';

try {
    $URI = parse_url($_SERVER['REQUEST_URI']);

    switch ($URI['path']) {
        case '/new_game':
            $response = GameController::newGame();
            break;
        case '/new_move':
            $response = GameController::newMove(urldecode($URI['query']));
            break;
        case '/game_state':
            $response = GameController::getGameState();
            break;
        case '/game_status':
            $response = GameController::getGameStatus();
            break;
        default:
            $response = [
                'ok' => false,
                'message' => 'Invalid request',
            ];
    }

    echo json_encode($response);
} catch (Exception $e) {
    return [
        'ok' => false,
        'message' => $e->getMessage(),
    ];
}

