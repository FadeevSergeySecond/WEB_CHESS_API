<?php

//require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Figure.php');
include 'Figure.php';

class Bishop extends Figure
{
    public static function validateMove($newMove, $board)
    {
        try {
            $newMove = json_decode($newMove, true);
            $fromLine = $newMove['from']['line'];
            $fromColumn = $newMove['from']['column'];
            $toLine = $newMove['to']['line'];
            $toColumn = $newMove['to']['column'];

            $deltaLine = $toLine - $fromLine;
            $deltaColumn = ord($fromColumn) - ord($toColumn);

            if (!(abs($deltaLine) - abs($deltaColumn)) == 0) {
                return [
                    'ok' => false,
                    'message' => 'A bishop cannot make such a move',
                ];
            }

            $normDeltaLine = $deltaLine / abs($deltaLine);
            $normDeltaColumn = $deltaColumn / abs($deltaColumn);
            for ($i = $fromLine + $normDeltaLine, $j = $fromColumn + $normDeltaColumn;
                 $i != $toLine && $j != $toColumn;
                 $i += $normDeltaLine, $j += $normDeltaColumn) {
                if ($board[$i][$j] != null) {
                    return [
                        'ok' => false,
                        'message' => 'A bishop cannot jump over shapes',
                    ];
                }
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
            'name' => 'Bishop',
            'color' => $this->color,
        ];
    }
}