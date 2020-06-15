<?php

require_once __DIR__ . '/Figure.php';

class King extends Figure
{
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