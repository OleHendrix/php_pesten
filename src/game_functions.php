<?php

declare(strict_types=1);

require_once 'Card.php';
require_once 'Player.php';

function printPlayerNames(array $players): void {
    $playerNames = array_map(fn($p) => $p->getName(), $players);
    fwrite(STDOUT, "Starting game with " . implode(', ', $playerNames) . "\n");
}

// RestDeck functions
function createRestDeck(): array {
    $restDeck = [];
    $ranks = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
    $suits = ['♥', '♦', '♣', '♠'];
    
    foreach ($ranks as $rank) {
        foreach ($suits as $suit) {
            $restDeck[] = new Card($rank, $suit);
        }
    }
    
    return $restDeck;
}

function drawCard(array $deck): array {
    if (empty($deck)) {
        return ['card' => null, 'deck' => $deck];
    }
    
    $card = array_shift($deck);
    return ['card' => $card, 'deck' => $deck];
}

// PlayDeck functions
function createPlayDeck(Card $startCard): array {
    return [$startCard];
}

function addCardToPlayDeck(array $playDeck, Card $card): array {
    array_unshift($playDeck, $card);
    return $playDeck;
}

function getTopCard(array $playDeck): ?Card {
    return $playDeck[0] ?? null;
}

// Game functions
function dealCards(array $players, array $restDeck, int $cardsPerPlayer = 7): array {
    for ($i = 0; $i < $cardsPerPlayer; $i++) {
        foreach ($players as $player) {
            $result = drawCard($restDeck);
            if ($result['card']) {
                $player->takeCard($result['card']);
                $restDeck = $result['deck'];
            }
        }
    }
    
    return $restDeck;
}

function printHands(array $players): void {
    foreach ($players as $player) {
        $player->printHand();
    }
}

function printTopCard(array $playDeck): void {
    $topCard = getTopCard($playDeck);
    if ($topCard) {
        fwrite(STDOUT, "Top card is: {$topCard->toString()}\n");
    }
}

function startGame(array $players, array $playDeck, array $restDeck): void {
    while (true) {
        foreach ($players as $player) {
            $result = $player->takeTurn($playDeck, $restDeck);
			// Update the play deck and rest deck after each player's turn
            $playDeck = $result['playDeck'];
            $restDeck = $result['restDeck'];
            
            if ($result['won']) {
                return;
            }
        }
    }
}