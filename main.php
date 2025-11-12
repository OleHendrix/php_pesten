<?php

declare(strict_types=1);

require_once 'Game.php';
require_once 'RestDeck.php';
require_once 'PlayDeck.php';
require_once 'Player.php';
function main() {
  $restDeck = new RestDeck();
  $players = [
    new Player("Oleg"),
    new Player("John"),
    new Player("Jane"),
    new Player("Jim")
  ];

  $restDeck->shuffle();
  $startCard = $restDeck->drawCard();
  $playDeck = new PlayDeck($startCard);
  
  $game = new Game($restDeck, $playDeck, $players);
  $game->playGame();
}

main();