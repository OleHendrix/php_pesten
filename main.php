<?php
require_once 'Game.php';
function main() {
  $game = new Game(["Oleg", "John", "Jane", "Jim"]);
  $game->playGame();
}

main();