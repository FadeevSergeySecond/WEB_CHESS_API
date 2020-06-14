<?php

require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Figure.php');

class Pawn extends Figure
{
    // guaranteed that from and to are on the board
    public static function validateMove($newMove, $board)
    {
        try {
            $newMove = json_decode($newMove, true);

            $fromLine = $newMove['from']['line'];
            $fromColumn = $newMove['from']['column'];
            $toLine = $newMove['to']['line'];
            $toColumn = $newMove['to']['column'];

            $deltaLine = $toLine - $fromLine;
            $deltaColumn = ord($toColumn) - ord($fromColumn);

            $ok = false;
            $message = 'Invalid move';

            $colorOfPawn = $board[$fromLine][$fromColumn]['color'];
            $signColor = $colorOfPawn == 'white' ? 1 : -1;

            if (($fromLine == 2 || $fromLine == 7) && $deltaLine == $signColor * 2 && $deltaColumn == 0) {
                if ($board[$fromLine + $signColor][$toColumn] != null) {
                    $ok = false;
                    $message = 'A pawn cannot jump over shapes';
                } else if ($board[$toLine][$toColumn] != null) {
                    $ok = false;
                    $message = 'A pawn cannot move forward into the cell in which the chess piece stands';
                } else {
                    $ok = true;
                    $message = 'The move is valid';
                }
            }
            if ($deltaLine == $signColor && $deltaColumn == 0) {
                if ($board[$toLine][$toColumn] != null) {
                    $ok = false;
                    $message = 'A pawn cannot move forward into the cell in which the chess piece stands';
                } else {
                    $ok = true;
                    $message = 'The move is valid';
                }
            }
            if ($deltaLine == $signColor && abs($deltaColumn) == 1) {
                if ($board[$toLine][$toColumn] == null) {
                    $ok = false;
                    $message = 'A move not forward is possible only to take someone else\'s piece';
                } else {
                    $ok = true;
                    $message = 'The move is valid';
                }
            }

            return [
                'ok' => $ok,
                'message' => $message,
            ];

        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'Pawn',
            'color' => $this->color,
        ];
    }
}