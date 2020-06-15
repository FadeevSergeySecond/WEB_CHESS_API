<?php

class GameService
{
    public static function validateMove($newMove, $gameState)
    {
        try {

            $nextMove = $gameState['nextMove'];
            $board = $gameState['board'];
            $gameOver = $gameState['gameOver'];

            $fromLine = $newMove['from']['line'];
            $fromColumn = $newMove['from']['column'];
            $toLine = $newMove['to']['line'];
            $toColumn = $newMove['to']['column'];
            $figureOnStartCell = $board[$fromLine][$fromColumn];
            $figureOnFinishCell = $board[$toLine][$toColumn];

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

            if (($fromLine - $toLine == 0) && (ord($fromColumn) - ord($toColumn) == 0)) {
                return [
                    'ok' => false,
                    'message' => 'You cannot make the move to the same cage.',
                ];
            }

            $result = null;

            switch ($figureOnStartCell['name']) {
                case 'King':
                    $result = King::validateMove($newMove, $board);
                    break;
                case 'Queen':
                    $result = Queen::validateMove($newMove, $board);
                    break;
                case 'Rook':
                    $result = Rook::validateMove($newMove, $board);
                    break;
                case 'Knight':
                    $result = Knight::validateMove($newMove, $board);
                    break;
                case 'Bishop':
                    $result = Bishop::validateMove($newMove, $board);
                    break;
                case 'Pawn':
                    $result = Pawn::validateMove($newMove, $board);
                    break;
            }

            if (!$result['ok']) {
                return $result;
            }

            if ($figureOnStartCell['color'] == $figureOnFinishCell['color']) {
                return [
                    'ok' => false,
                    'message' => 'You can’t go to the field occupied by your figure',
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


    public static function updateBoard($newMove)
    {
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

    public static function kingWillInCheck($newMove): bool
    {
        $gameState = json_decode(file_get_contents('board.txt'), true);

        $fromLine = $newMove['from']['line'];
        $fromColumn = $newMove['from']['column'];
        $toLine = $newMove['to']['line'];
        $toColumn = $newMove['to']['column'];

        $figureOnStartCell = $gameState['board'][$fromLine][$fromColumn];
        $gameState['board'][$fromLine][$fromColumn] = null;
        $gameState['board'][$toLine][$toColumn] = $figureOnStartCell;
        $gameState['nextMove'] = $figureOnStartCell['color'] == 'white' ? 'black' : 'white';
        return self::kingInCheck($gameState, $figureOnStartCell['color']);
    }

    public static function kingInCheck($gameState, $colorOfCheckedKing): bool
    {
        $board = $gameState['board'];
        $lineOfKing = null;
        $columnOfKing = null;
        for ($i = 1; $i <= 8; $i++) {
            for ($j = 'a'; $j != 'i'; $j++) {
                if ($board[$i][$j]['name'] == 'King' && $board[$i][$j]['color'] == $colorOfCheckedKing) {
                    echo $i . " " . $j . "\n";
                    $lineOfKing = $i;
                    $columnOfKing = $j;
                }
            }
        }

        for ($i = 1; $i <= 8; $i++) {
            for ($j = 'a'; $j != 'i'; $j++) {
                if ($board[$i][$j]['color'] != $colorOfCheckedKing) {
                    $move = [
                        'from' => ['line' => $i, 'column' => $j],
                        'to' => ['line' => $lineOfKing, 'column' => $columnOfKing],
                    ];
                    $tempFigureCanBeatTheKing = self::validateMove($move, $gameState);
                    echo $i . " " . $j . " " . $tempFigureCanBeatTheKing['message'] . "\n";
                    if ($tempFigureCanBeatTheKing['ok']) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

//    public static function kingInCheckmate($gameState): bool
//    {
//
//
//    }
}