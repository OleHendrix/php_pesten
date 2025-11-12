<?php

declare(strict_types=1);

require_once 'game_functions.php';

function main() {
    // Create players (objects)
    $players = [
        new Player("Oleg"),
        new Player("John"),
        new Player("Jane"),
        new Player("Jim")
    ];
    
    // Print player names
    $playerNames = array_map(fn($p) => $p->getName(), $players);
    fwrite(STDOUT, "Starting game with " . implode(', ', $playerNames) . "\n");
    
    // Create and shuffle rest deck (functional)
    $restDeck = createRestDeck();
    $restDeck = shuffleDeck($restDeck);
    
    // Deal cards to players (functional approach)
    $restDeck = dealCards($players, $restDeck);
    
    // Print hands
    printHands($players);
    
    // Create play deck with first card
    $result = drawCard($restDeck);
    $playDeck = createPlayDeck($result['card']);
    $restDeck = $result['deck'];
    
    // Print top card
    printTopCard($playDeck);
    
    // Play the game
    playGame($players, $playDeck, $restDeck);
}

main();