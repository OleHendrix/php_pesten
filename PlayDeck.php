<?php

class PlayDeck {
    private array $cards = [];
    
    public function __construct(Card $startCard) {
        $this->cards = [$startCard];
    }

    public function addCard(Card $card): void {
        array_unshift($this->cards, $card);
    }

    public function getTopCard(): ?Card {
        return $this->cards[0];
    }
}