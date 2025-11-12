<?php

declare(strict_types=1);

class Card {
    private string $rank;
    private string $suit;
    
    public function __construct(string $rank, string $suit) {
        $this->rank = $rank;
        $this->suit = $suit;
    }
    
    public function getRank(): string {
        return $this->rank;
    }
    
    public function getSuit(): string {
        return $this->suit;
    }
    
    public function matchesCard(Card $topCard): bool {
        return $this->rank === $topCard->getRank() || 
               $this->suit === $topCard->getSuit();
    }
    
    public function toString(): string {
        return "{$this->suit}{$this->rank}";
    }
}