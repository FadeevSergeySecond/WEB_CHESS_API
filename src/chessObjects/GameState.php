<?php

require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Bishop.php');
require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/King.php');
require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Knight.php');
require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Pawn.php');
require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Queen.php');
require_once('/Users/Fadeev/Downloads/vk/src/chessObjects/Rook.php');

class GameState implements JsonSerializable
{
    public static $nextMove;
    public static $gameOver;
    public static $board;

    public function __construct()
    {
        self::$nextMove = 'white';
        self::$gameOver = false;

        $lines = [1, 2, 3, 4, 5, 6, 7, 8];
        $columns = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];

        foreach (array_slice($lines, 2, 4) as $line) {
            foreach ($columns as $column) {
                self::$board[$line][$column] = null;
            }
        }

        foreach ($columns as $column) {
            self::$board[2][$column] = new Pawn('white');
            self::$board[7][$column] = new Pawn('black');
        }

        self::$board[1]['a'] = new Rook('white');
        self::$board[8]['a'] = new Rook('black');

        self::$board[1]['h'] = new Rook('white');
        self::$board[8]['h'] = new Rook('black');

        self::$board[1]['b'] = new Knight('white');
        self::$board[8]['b'] = new Knight('black');
        self::$board[1]['g'] = new Knight('white');
        self::$board[8]['g'] = new Knight('black');

        self::$board[1]['c'] = new Bishop('white');
        self::$board[8]['c'] = new Bishop('black');
        self::$board[1]['f'] = new Bishop('white');
        self::$board[8]['f'] = new Bishop('black');

        self::$board[1]['d'] = new Queen('white');
        self::$board[8]['d'] = new Queen('black');

        self::$board[1]['e'] = new King('white');
        self::$board[8]['e'] = new King('black');
    }

    public function jsonSerialize()
    {
        return [
            'nextMove' => self::$nextMove,
            'gameOver' => self::$gameOver,
            'board' => self::$board,
        ];
    }
}