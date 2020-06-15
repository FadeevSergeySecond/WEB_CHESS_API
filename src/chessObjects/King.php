<?php

require_once __DIR__ . '/Figure.php';

class King extends Figure
{

    /**
     * return [
     *     'ok' => res,
     *     'message' => mes
     * ];
     * res == true and mes == 'The move is valid', if $newMove
     * can be made by King on the $board
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
            $fromLine = $newMove['from']['line'];
            $fromColumn = $newMove['from']['column'];
            $toLine = $newMove['to']['line'];
            $toColumn = $newMove['to']['column'];

            $deltaLine = $toLine - $fromLine;
            $deltaColumn = ord($toColumn) - ord($fromColumn);
            if (abs($deltaLine) > 1 || abs($deltaColumn) > 1) {
                return [
                    'ok' => false,
                    'message' => 'A king cannot make such a move',
                ];
            }

            return [
                'ok' => true,
                'message' => 'The move is valid',
            ];
        } catch (Exception $e) {
            return [
                'ok' => false,
                'message' => 'Exception in King::validateMove with message: ' . $e->getMessage(),
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