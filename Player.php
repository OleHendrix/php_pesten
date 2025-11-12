<?php

require_once 'Card.php';

class Player {
    private string $name;
    private array $hand = [];  
    
    public function __construct(string $name) {
        $this->name = $name;
    }

    public function printHand(): void {
        $cards = array_map(fn($card) => "{$card->getSuit()}{$card->getRank()}", $this->hand);
        fwrite(STDOUT, "{$this->name} has been dealt: " . implode(', ', $cards) . PHP_EOL);
    }
  
    
    public function findAndPlayCard(Card $topCard): ?Card {
        foreach ($this->hand as $index => $card) {
            if ($card->matchesCard($topCard)) {
                return $this->removeCardAtIndex($index);
            }
        }
        return null;
    }

    public function takeCard(Card $card): void {
        $this->hand[] = $card;
    }

    public function checkCardsRemaining(): int {
      $cardCount = $this->getCardCount();
      if ($cardCount == 1) {
        fwrite(STDOUT, "{$this->name} has 1 card remaining\n");
      }
      return $cardCount;
    }
    
    private function removeCardAtIndex(int $index): Card {
        $card = $this->hand[$index];
        array_splice($this->hand, $index, 1);
        return $card;
    }
    
    public function hasCards(): bool {
        return count($this->hand) > 0;
    }
    
    public function getCardCount(): int {
        return count($this->hand);
    }
    
    public function getName(): string {
        return $this->name;
    }
}