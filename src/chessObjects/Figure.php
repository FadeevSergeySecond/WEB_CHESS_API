<?php

abstract class Figure implements JsonSerializable
{
    public $color;

    abstract protected static function validateMove($newMove, $board);

    public function __construct($color)
    {
        $this->color = $color;
    }
}