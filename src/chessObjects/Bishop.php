<?php

require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Figure.php');

class Bishop extends Figure
{
    public static function validateMove($newTurn, &$board)
    {
        // TODO: Implement validateMove() method.
        return true;
    }

    public function jsonSerialize()
    {
        return [
            'name' => 'Bishop',
            'color' => $this->color,
        ];
    }
}