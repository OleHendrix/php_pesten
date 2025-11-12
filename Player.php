<?php

declare(strict_types=1);

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

    public function takeTurn(PlayDeck $playDeck, RestDeck $restDeck): bool {
        $topCard = $playDeck->getTopCard();
        $card = $this->findAndPlayCard($topCard);
        
        if ($card) {
            $playDeck->addCard($card);
            fwrite(STDOUT, "{$this->name} plays {$card->getSuit()}{$card->getRank()}\n");
            
            if ($this->checkCardsRemaining() == 0) {
                fwrite(STDOUT, "{$this->name} has won\n");
                return true;
            }
        } else {
            $card = $restDeck->drawCard();
            if ($card) {
                $this->takeCard($card);
                fwrite(STDOUT, "{$this->name} does not have a suitable card, taking from deck {$card->getSuit()}{$card->getRank()} \n");
            }
        }
        
        return false;
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
      $cardCount = count($this->hand);
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
    
    public function getName(): string {
        return $this->name;
    }
}