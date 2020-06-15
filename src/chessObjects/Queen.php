<?php

require_once __DIR__ . '/Figure.php';

class Queen extends Figure
{
    /**
     * return [
     *     'ok' => res,
     *     'message' => mes
     * ];
     * res == true and mes == 'The move is valid', if $newMove
     * can be made by Queen on the $board
     *
     * else res == false and mes contains the reason why the move is impossible
     *
     * @param array $newMove
     * @param array $board
     * @return array
     */

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
                    'ok' => false,
                    'message' => 'A queen cannot jump over shapes',
                ];
            } else {
                return [
                    'ok' => false,
                    'message' => 'A queen cannot make such a move',
                ];
            }
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Exception in Queen::validateMove with message: ' . $e->getMessage(),
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