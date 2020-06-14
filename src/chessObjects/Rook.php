<?php

require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Figure.php');

class Rook extends Figure
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

            $deltaLine = $fromLine - $toLine;
            $deltaColumn = ord($fromColumn) - ord($toColumn);

            if ($deltaLine != 0 && $deltaColumn != 0) {
                return [
                    'ok' => false,
                    'message' => 'A rook cannot make such a move',
                ];
            }

            if ($deltaLine == 0) {
                $normDeltaColumn = $deltaColumn / abs($deltaColumn);
                for ($j = $fromColumn + $normDeltaColumn; $j != $toColumn; $j += $normDeltaColumn) {
                    if ($board[$toLine][$j]) {
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
                    if ($board[$i][$toColumn]) {
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