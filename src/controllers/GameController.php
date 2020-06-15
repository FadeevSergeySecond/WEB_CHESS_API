<?php

require_once __DIR__ . '/../services/GameService.php';
require_once __DIR__ . '/../models/GameModel.php';
require_once __DIR__ . '/../chessObjects/GameState.php';

class GameController
{
    public static function newGame()
    {
        try {
            file_put_contents('data/gameState.json', json_encode(new GameState()));
            return [
                'ok' => true,
                'message' => 'New game started',
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function newMove($gameData)
    {
        try {
            $validateInputResult = GameModel::validateInput($gameData);
            if (!$validateInputResult['ok']) {
                return $validateInputResult;
            }

            $gameDataArray = json_decode($gameData, true);
            $gameState = json_decode(file_get_contents('data/gameState.json'), true);

            $validateMoveResult = GameService::validateMove($gameDataArray, $gameState);
            if (!$validateMoveResult['ok']) {
                return $validateMoveResult;
            }

            if (GameService::kingWillInCheck($gameDataArray, $gameState)) {
                if (GameService::kingInCheckmate($gameState, $gameState['nextMove'])) {
                    $colorLosingSide = $gameState['nextMove'];

                    GameService::setGameStatus("Game over, $colorLosingSide lost");

                    return [
                        'ok' => false,
                        'message' => "A move is impossible. Checkmate declared to the $colorLosingSide king",
                    ];

                } else {
                    return [
                        'ok' => false,
                        'message' => 'As a result of the move, the king will be under the check',
                    ];
                }
            }

            return GameService::updateBoard($gameData);

        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Exception in GameController::newMove with message: ' . $e->getMessage()
            ];
        }
    }

    public static function getGameState()
    {
        return file_get_contents('data/gameState.json');
    }

    public static function getGameStatus()
    {
        return json_decode(file_get_contents('data/gameState.json'), true)['gameStatus'];
    }
}