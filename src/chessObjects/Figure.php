<?php

abstract class Figure implements JsonSerializable
{
    public $color;

    abstract protected static function validateMove($newTurn, &$board);

    public function __construct($color)
    {
        $this->color = $color;
    }
}