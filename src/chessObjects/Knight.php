<?php

require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Figure.php');

class Knight extends Figure
{
    // guaranteed that from and to are on the board
    public static function validateMove($newMove, $board)
    {
        try {
            $fromLine = $newMove['from']['line'];
            $fromColumn = $newMove['from']['column'];
            $toLine = $newMove['to']['line'];
            $toColumn = $newMove['to']['column'];

            $deltaLine = $toLine - $fromLine;
            $deltaColumn = ord($toColumn) - ord($fromColumn);

            if ((abs($deltaLine) == 1 && abs($deltaColumn) == 2) ||
                (abs($deltaLine) == 2 && abs($deltaColumn) == 1)) {
                return [
                    'ok' => true,
                    'message' => 'The move is valid',
                ];
            } else {
                return [
                    'ok' => false,
                    'message' => 'A knight cannot make such a move',
                ];
            }
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
            'name' => 'Knight',
            'color' => $this->color,
        ];
    }
}