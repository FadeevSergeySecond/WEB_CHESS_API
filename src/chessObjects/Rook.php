<?php

require_once __DIR__ . '/Figure.php';

class Rook extends Figure
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

            if ($deltaLine != 0 && $deltaColumn != 0) {
                return [
                    'ok' => false,
                    'message' => 'A rook cannot make such a move',
                ];
            }

            if ($deltaLine == 0) {
                $normDeltaColumn = $deltaColumn / abs($deltaColumn);
                for ($j = chr(ord($fromColumn) + $normDeltaColumn); $j != $toColumn; $j = chr(ord($j) + $normDeltaColumn)) {
                    if ($board[$toLine][$j] != null) {
                        return [
                            'ok' => false,
                            'message' => 'A rook cannot jump over shapes',
                        ];
                    }
                }
            }

            if ($deltaColumn == 0) {
                $normDeltaLine = $deltaLine / abs($deltaLine);
                for ($i = $fromLine + $normDeltaLine; $i != $toLine; $i += $normDeltaLine) {
                    if ($board[$i][$toColumn] != null) {
                        return [
                            'ok' => false,
                            'message' => 'A rook cannot jump over shapes',
                        ];
                    }
                }
            }

            return [
                'ok' => true,
                'message' => 'The move is valid',
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
            'name' => 'Rook',
            'color' => $this->color,
        ];
    }
}