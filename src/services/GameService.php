<?php

class GameService
{
    // ['from' => int, 'to' => string from one char];

    // newMove = [
    //  'from' => ['line' => int, 'column' => string,],
    //  'to'   => ['line' => int, 'column' => string,],
    // ]
    public static function validateMove($newMove)
    {
        try {
            $gameState = json_decode(file_get_contents('board.txt'), true);
            $newMove = json_decode($newMove, true);

            $nextMove = $gameState['nextMove'];
            $board = $gameState['board'];
            $gameOver = $gameState['gameOver'];

            $fromLine = $newMove['from']['line'];
            $fromColumn = $newMove['from']['column'];
            $figureOnStartCell = $board[$fromLine][$fromColumn];

            if ($gameOver == true) {
                return [
                    'ok' => false,
                    'message' => 'the game is already over',
                ];
            }

            if ($figureOnStartCell == null) {
                return [
                    'ok' => false,
                    'message' => 'trying to resemble a non-existent figure',
                ];
            }

            if ($nextMove != $figureOnStartCell['color']) {
                return [
                    'ok' => false,
                    'message' => 'The move must be made by the other side',
                ];
            }

            $result = null;

            switch ($figureOnStartCell['name']) {
                case 'King':
                    $result = King::validateMove($newMove,$board);
                    break;
                case 'Queen':
                    $result = Queen::validateMove($newMove,$board);
                    break;
                case 'Rook':
                    $result = Rook::validateMove($newMove,$board);
                    break;
                case 'Knight':
                    $result = Knight::validateMove($newMove,$board);
                    break;
                case 'Bishop':
                    $result = Bishop::validateMove($newMove,$board);
                    break;
                case 'Pawn':
                    $result = Pawn::validateMove($newMove,$board);
                    break;
            }

            if (!$result) {
                return [
                    'ok' => false,
                    'message' => 'Invalid move for this fig',
                ];
            }

            return [
                'ok' => true,
                'message' => 'move valid',
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => "exception " . $e->getMessage(),
            ];
        }
    }


    public static function updateBoard($newMove) {
        try {
            $newMove = json_decode($newMove, true);

            $gameState = json_decode(file_get_contents('board.txt'), true);

            $nextMove = $gameState['nextMove'];
            $board = $gameState['board'];
            $gameOver = $gameState['gameOver'];

            $fromLine = $newMove['from']['line'];
            $fromColumn = $newMove['from']['column'];
            $toLine = $newMove['to']['line'];
            $toColumn = $newMove['to']['column'];

            $figureOnStartCell = $board[$fromLine][$fromColumn];
            $figureOnFinishCell = $board[$toLine][$toColumn];

            if ($nextMove == 'white') {
                $nextMove = 'black';
            } else {
                $nextMove = 'white';
            }

            $board[$fromLine][$fromColumn] = null;
            $board[$toLine][$toColumn] = $figureOnStartCell;

            if ($figureOnFinishCell['name'] == 'King') {
                $gameOver = true;
            }

            GameState::$nextMove = $nextMove;
            GameState::$gameOver = $gameOver;
            GameState::$board = $board;

            echo GameState::$nextMove . "\n";

            file_put_contents('board.txt', json_encode([
                'nextMove' => GameState::$nextMove,
                'gameOver' => GameState::$gameOver,
                'board' => GameState::$board,
            ]));

            return [
                'ok' => true,
                'message' => 'Succ update',
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Exception in update - ' . $e->getMessage(),
            ];
        }
    }
}