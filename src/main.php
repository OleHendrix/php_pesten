<?php

declare(strict_types=1);

require_once 'game_functions.php';

function main() {
    $playerNames = ["Elwin", "Sasha", "Mathijs", "Ole"];
    $players = array_map(fn($name) => new Player($name), $playerNames);
    
    printPlayerNames($players);
    
    $restDeck = createRestDeck();
    shuffle($restDeck);
    
    $restDeck = dealCards($players, $restDeck);
    
    printHands($players);
    
    $result = drawCard($restDeck);
    $playDeck = createPlayDeck($result['card']);
    $restDeck = $result['deck'];
    
    printTopCard($playDeck);
    
    startGame($players, $playDeck, $restDeck);
}

main();