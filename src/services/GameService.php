<?php

class GameService
{

    /**
     * valudateInput return [
     *     'ok' => res,
     *     'message' => mes,
     * ]
     * res == true and mes == 'Valid move' if $newMove is json that describing
     * the move that can be made according to the rules
     *
     * else result = false
     * and mes contains the reason why the move cannot be made
     *
     * @param array $newMove
     * @param array $gameState
     * @return array
     */

    public static function validateMove($newMove, $gameState)
    {
        try {
            $nextMove = $gameState['nextMove'];
            $board = $gameState['board'];
            $gameStatus = $gameState['gameStatus'];

            $fromLine = $newMove['from']['line'];
            $fromColumn = $newMove['from']['column'];
            $toLine = $newMove['to']['line'];
            $toColumn = $newMove['to']['column'];
            $figureOnStartCell = $board[$fromLine][$fromColumn];
            $figureOnFinishCell = $board[$toLine][$toColumn];

            if ($gameStatus != 'The game is on') {
                return [
                    'ok' => false,
                    'message' => $gameState,
                ];
            }

            if ($figureOnStartCell == null) {
                return [
                    'ok' => false,
                    'message' => 'Trying to resemble a non-existent figure',
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
                    'message' => 'It is impossible to make a move to the same cage',
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
                'message' => 'Valid move',
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Exception in GameService::validateMove with message: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Updates data/gameState.json
     * On the new data/gameState.json the move is made
     *
     * @param string $newMove
     * @return array
     */

    public static function updateBoard($newMove)
    {
        try {
            $newMove = json_decode($newMove, true);
            $gameState = json_decode(file_get_contents('data/gameState.json'), true);

            $nextMove = $gameState['nextMove'];
            $board = $gameState['board'];
            $gameStatus = $gameState['gameStatus'];

            $fromLine = $newMove['from']['line'];
            $fromColumn = $newMove['from']['column'];
            $toLine = $newMove['to']['line'];
            $toColumn = $newMove['to']['column'];

            $figureOnStartCell = $board[$fromLine][$fromColumn];

            if ($nextMove == 'white') {
                $nextMove = 'black';
            } else {
                $nextMove = 'white';
            }

            $board[$fromLine][$fromColumn] = null;
            if ($figureOnStartCell['name'] == 'Pawn' && ($toLine == 8 || $toLine == 1)) {
                $figureOnStartCell['name'] = 'Queen';
                $board[$toLine][$toColumn] = $figureOnStartCell;
            } else {
                $board[$toLine][$toColumn] = $figureOnStartCell;
            }


            file_put_contents('data/gameState.json', json_encode([
                'nextMove' => $nextMove,
                'gameStatus' => $gameStatus,
                'board' => $board,
            ]));

            return [
                'ok' => true,
                'message' => 'Update was successful',
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Exception in GameService::updateBoard with message: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * return true, if after $newMove the king of the walking side will be under the check
     * else return false
     *
     * @param array $newMove with new move
     * @param array $gameState
     * @return bool
     */

    public static function kingWillInCheck($newMove, $gameState): bool
    {
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

    /**
     * return true, if on $gameState king with color - $colorOfCheckedKing under the check
     * else return false
     *
     * @param array $gameState
     * @param string $colorOfCheckedKing for example 'white'
     * @return bool
     */

    public static function kingInCheck($gameState, $colorOfCheckedKing): bool
    {
        $board = $gameState['board'];
        $lineOfKing = null;
        $columnOfKing = null;

        self::findKing($board, $colorOfCheckedKing, $lineOfKing, $columnOfKing);

        for ($i = 1; $i <= 8; $i++) {
            for ($j = 'a'; $j != 'i'; $j++) {
                if ($board[$i][$j]['color'] != $colorOfCheckedKing) {
                    $move = [
                        'from' => ['line' => $i, 'column' => $j],
                        'to' => ['line' => $lineOfKing, 'column' => $columnOfKing],
                    ];
                    $tempFigureCanBeatTheKing = self::validateMove($move, $gameState);
                    if ($tempFigureCanBeatTheKing['ok']) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * return true, if on $gameState king with color - $colorOfCheckedKing under the check
     * else return false
     *
     * @param array $gameState
     * @param string $colorOfCheckedKing for example 'white'
     * @return bool
     */

    public static function kingInCheckmate($gameState, $colorOfCheckedKing): bool
    {
        $board = $gameState['board'];
        $lineOfKing = null;
        $columnOfKing = null;

        self::findKing($board, $colorOfCheckedKing, $lineOfKing, $columnOfKing);

        for ($i = 1; $i <= 8; $i++) {
            for ($j = 'a'; $j != 'i'; $j++) {
                if ($board[$i][$j]['color'] == $colorOfCheckedKing) {
                    for ($k = 1; $k <= 8; $k++) {
                        for ($m = 'a'; $m != 'i'; $m++) {
                            $move = [
                                'from' => ['line' => $i, 'column' => $j],
                                'to' => ['line' => $k, 'column' => $m],
                            ];
                            $canMove = self::validateMove($move, $gameState);
                            if ($canMove['ok']) {
                                if (!self::kingWillInCheck($move, $gameState)) {
                                    echo $i . " " . $j . " " . $k . " " . $m . "\n";
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param array $board array
     * @param string $colorOfCheckedKing string
     * @param int $lineOfKing from 1 to 8
     * @param string $columnOfKing single letter string 'a' to 'h'
     *
     */

    private static function findKing($board, $colorOfCheckedKing, &$lineOfKing, &$columnOfKing)
    {
        for ($i = 1; $i <= 8; $i++) {
            for ($j = 'a'; $j != 'i'; $j++) {
                if ($board[$i][$j]['name'] == 'King' && $board[$i][$j]['color'] == $colorOfCheckedKing) {
                    $lineOfKing = $i;
                    $columnOfKing = $j;

                    break;
                }
            }
        }
    }

    /**
     * @param string $newGameStatus
     * Record a new status of game in data/gameState.json
     */

    public static function setGameStatus($newGameStatus)
    {
        $gameState = json_decode(file_get_contents('data/gameState.json'), true);
        file_put_contents('data/gameState.json', json_encode([
            'nextMove' => $gameState['nextMove'],
            'gameStatus' => $newGameStatus,
            'board' => $gameState['board'],
        ]));
    }
}