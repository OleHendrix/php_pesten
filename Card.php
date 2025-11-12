<?php
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
    
    public function matchesCard(Card $otherCard): bool {
        return $this->rank === $otherCard->getRank() || 
               $this->suit === $otherCard->getSuit();
    }
    
    public function __toString(): string {
        return "{$this->rank} of {$this->suit}";
    }
}