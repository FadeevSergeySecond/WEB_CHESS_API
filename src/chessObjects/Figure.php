<?php

abstract class Figure implements JsonSerializable
{
    public $color;

    /**
     * @param array $newMove
     * @param array $board
     * @return array
     */
    abstract protected static function validateMove($newMove, $board);

    public function __construct($color)
    {
        $this->color = $color;
    }
}