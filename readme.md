# WEB CHEES API

Веб-сервис, который даёт возможность провести шахматную партию.

## API
Методы:
* Начать новую партию `new_game`
* Получить статус партии `game_state` 
* Получить состояние партии `game_status`
* Сделать ход `new_move`

### Метод new_game

Для того, чтобы обратиться к методу API `new_game` Вам необходимо выполнить запрос такого вида:  
`http://домен/new_game`, к примеру `http://localhost:8080/new_game`

В ответ на такой запрос Вы получите ответ в формате JSON:
```
{
    "ok": true,
    "message": "New game started"
}
```
если новая партия была успешно начата, или 
```
{
    "ok": false,
    "message": "Сообщение об ошибке"
}
```
если новая партия начата не была
### Метод game_status
Для того, чтобы обратиться к методу API `game_status` Вам необходимо выполнить запрос такого вида:  
`http://домен/game_status`, к примеру `http://localhost:8080/game_status`

В ответ на такой запрос Вы получите один из трех ответов: 
```
1) "The game is on"
2) "Game over, white win"
3) "Game over, black win"
```
### Метод game_state
Для того, чтобы обратиться к методу API `game_state` Вам необходимо выполнить запрос такого вида:  
`http://домен/game_state`, к примеру `http://localhost:8080/game_state`
В ответ на такой запрос Вы получите содержимое файла `data/gameState.json`

Пример того, что Вы получите, если выполните запрос после того, как начнете новую партию [здесь](https://github.com/FadeevSergey/WEB_CHEES_API/blob/master/src/data/gameState.json)

nextMove - хранит цвет того, кто ходит следующий  
gameStatus - хранит статус партии  
board - хранит состояни шахматной доски

board представляет собой JSON, в котором ключи "1" - "2" отвечают за номер строки. Значения для этих ключей - JSON, хранящий фигуры, находящиеся на соответствующих строках шахматной доски. 

Пример JSON, хранящего фигуры строки №1 в начале игры. 
```
 {
  "a": {
    "name": "Rook",
    "color": "white"
  },
  "h": {
    "name": "Rook",
    "color": "white"
  },
  "b": {
    "name": "Knight",
    "color": "white"
  },
  "g": {
    "name": "Knight",
    "color": "white"
  },
  "c": {
    "name": "Bishop",
    "color": "white"
  },
  "f": {
    "name": "Bishop",
    "color": "white"
  },
  "d": {
    "name": "Queen",
    "color": "white"
  },
  "e": {
    "name": "King",
    "color": "white"
  }
}
```
Если клетка пуста, то ее значение `null`
___

