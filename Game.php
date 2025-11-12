<?php

declare(strict_types=1);

require_once 'RestDeck.php';
require_once 'PlayDeck.php';
require_once 'Player.php';

class Game {
    private RestDeck $restDeck;
    private PlayDeck $playDeck;
    private array $players = []; 
    private const CARDS_PER_PLAYER = 7;

    public function __construct(RestDeck $restDeck, PlayDeck $playDeck, array $players) {
        $this->restDeck = $restDeck;
        $this->playDeck = $playDeck;
        $this->players = $players;
        
        $playerNames = array_map(fn($p) => $p->getName(), $players);
        fwrite(STDOUT, "Starting game with " . implode(', ', $playerNames) . "\n");

        $this->restDeck->shuffle();
        $this->dealCards();
        $this->printHands();
        $this->printTopCard();
    }

    private function dealCards(): void {
        for ($i = 0; $i < self::CARDS_PER_PLAYER; $i++) {
            foreach ($this->players as $player) {
                $card = $this->restDeck->drawCard();
                if ($card) {
                    $player->takeCard($card);
                }
            }
        }
    }

    private function printHands(): void {
        foreach ($this->players as $player) {
            $player->printHand();
        }
    }

    private function printTopCard(): void {
        $topCard = $this->playDeck->getTopCard();
        if ($topCard) {
            fwrite(STDOUT, "Top card is: {$topCard->getSuit()}{$topCard->getRank()}\n");
        }
    }

    public function playGame(): void {
        while (true) {
            foreach ($this->players as $player) {
                if ($player->takeTurn($this->playDeck, $this->restDeck)) {
                    return; // Player has won
                }
            }
        }
    }
}