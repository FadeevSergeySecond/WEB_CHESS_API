<?php

require_once __DIR__ . '/Figure.php';

class Knight extends Figure
{
    /**
     * return [
     *     'ok' => res,
     *     'message' => mes
     * ];
     * res == true and mes == 'The move is valid', if $newMove
     * can be made by Knight on the $board
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
                'message' => 'Exception in Knight::validateMove with message: ' . $e->getMessage(),
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