<?php

declare(strict_types=1);

require_once 'game_functions.php';

function main() {
    $players = [
        new Player("Elwin"),
        new Player("Sasha"),
        new Player("Jane"),
        new Player("Ole")
    ];
    
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