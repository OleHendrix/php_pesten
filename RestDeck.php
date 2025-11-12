<?php
require_once 'Card.php';

class RestDeck {
    private array $cards = [];
    
    public function __construct() {
        $ranks = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
        $suits = ['♥', '♦', '♣', '♠'];
        foreach ($ranks as $rank) {
            foreach ($suits as $suit) {
                $this->cards[] = new Card($rank, $suit);
            }
        }
    }

    public function shuffle(): void {
        shuffle($this->cards);
    }

    public function drawCard(): ?Card {
        if ($this->isEmpty()) {
            return null;
        }
        return array_shift($this->cards);
    }

    public function getTopCard(): ?Card {
        if ($this->isEmpty()) {
            return null;
        }
        return $this->cards[0];
    }
    
    public function isEmpty(): bool {
        return count($this->cards) === 0;
    }
    
    public function getCardCount(): int {
        return count($this->cards);
    }
}