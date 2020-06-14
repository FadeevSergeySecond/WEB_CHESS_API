<?php

require_once('/Users/Fadeev/Downloads/vk/src/services/GameService.php');
require_once('/Users/Fadeev/Downloads/vk/src/models/GameModel.php');

require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/GameState.php');

class GameController
{
    public static function startGame()
    {
        try {
            file_put_contents('board.txt', json_encode(new GameState()));
            return [
                'ok' => true,
                'message' => 'its okey'
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }

//{"from": {"line": 1, "column": "a"}, "to": {"line": 6, "column": "b"}}
    public static function makeAMove($gameData)
    {
        try {
            $validateInputResult = GameModel::validateInput($gameData);
            if (!$validateInputResult['ok']) {
                return $validateInputResult;
            }

            $validateMoveResult = GameService::validateMove($gameData);
            if (!$validateMoveResult['ok']) {
                return $validateMoveResult;
            }

            $updateResult = GameService::updateBoard($gameData);

            return $updateResult;
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public static function getStatistics()
    {
        try {
            return file_get_contents('board.txt');
        } catch (Exception $e) {
            //
        }
    }
}