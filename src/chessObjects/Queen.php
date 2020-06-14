<?php

require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Figure.php');

class Queen extends Figure
{
    // guaranteed that from and to are on the board
    public static function validateMove($newMove, $board)
    {
        try {
            $validMoveForBishop = Bishop::validateMove($newMove, $board);
            $validMoveForRook = Rook::validateMove($newMove, $board);

            if ($validMoveForBishop['ok'] || $validMoveForRook['ok']) {
                return [
                    'ok' => true,
                    'message' => 'The move is valid',
                ];
            } else if ($validMoveForBishop['message'] == 'A bishop cannot jump over shapes' ||
                $validMoveForRook['message'] == 'A rook cannot jump over shapes') {
                return [
                    'ok' => true,
                    'message' => 'A queen cannot jump over shapes',
                ];
            } else {
                return [
                    'ok' => true,
                    'message' => 'A queen cannot make such a move',
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
            'name' => 'Queen',
            'color' => $this->color,
        ];
    }
}