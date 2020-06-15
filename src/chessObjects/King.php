<?php

require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Figure.php');

class King extends Figure
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
            echo $deltaLine . "\n";
            if (abs($deltaLine) > 1 || abs($deltaColumn) > 1) {
                return [
                    'ok' => false,
                    'message' => 'A king cannot make such a move',
                ];
            }

            return [
                'ok' => true,
                'message' => 'the move is valid',
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
            'name' => 'King',
            'color' => $this->color,
        ];
    }
}