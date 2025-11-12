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
        $cards = array_map(fn($card) => $card->toString(), $this->hand);
        fwrite(STDOUT, "{$this->name} has been dealt: " . implode(', ', $cards) . "\n");
    }

    public function takeTurn(array $playDeck, array $restDeck): array {
        $topCard = getTopCard($playDeck);
        $card = $this->findAndPlayCard($topCard);
        
        if ($card) {
            $playDeck = addCardToPlayDeck($playDeck, $card);
            fwrite(STDOUT, "{$this->name} plays {$card->toString()}\n");
            
            if ($this->checkCardsRemaining() == 0) {
                fwrite(STDOUT, "{$this->name} has won.\n");
                return ['playDeck' => $playDeck, 'restDeck' => $restDeck, 'won' => true];
            }
        } else {
            $result = drawCard($restDeck);
            // If the result is not null, take the card from the deck
            if ($result['card']) {
                $this->takeCard($result['card']);
                // Update the rest deck
                $restDeck = $result['deck'];
                fwrite(STDOUT, "{$this->name} does not have a suitable card, taking from deck {$result['card']->toString()} \n");
            }
            // otherwise, skip the turn
        }
        
        return ['playDeck' => $playDeck, 'restDeck' => $restDeck, 'won' => false];
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
          fwrite(STDOUT, "{$this->name} has 1 card remaining!\n");
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