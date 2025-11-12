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

class Player {
    private string $name;
    private array $hand = [];  
    private int $index_to_play = 0;
    
    public function __construct(string $name) {
        $this->name = $name;
    }

    public function printHand(): void {
        $cards = array_map(fn($card) => "{$card->getSuit()}{$card->getRank()}", $this->hand);
        fwrite(STDOUT, "{$this->name} has been dealt: " . implode(', ', $cards) . PHP_EOL);
    }
    
    public function addCard(Card $card): void {
        $this->hand[] = $card;
    }
    
    public function findPlayableCard(Card $topCard): ?Card {
        foreach ($this->hand as $index => $card) {
            if ($card->matchesCard($topCard)) {
                return $this->removeCardAtIndex($index);
            }
        }
        return null;
    }

    public function canPlayCard(Card $topCard): bool {
        foreach ($this->hand as $index => $card) {
            if ($card->matchesCard($topCard)) {
                $this->index_to_play = $index;
                return true;
            }
        }
        return false;
    }

    public function playCard(): Card {
        $card = $this->hand[$this->index_to_play];
        array_splice($this->hand, $this->index_to_play, 1);
        return $card;
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
        return $this->cards[0];
    }
    
    public function isEmpty(): bool {
        return count($this->cards) === 0;
    }
    
    public function getCardCount(): int {
        return count($this->cards);
    }
}

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

class Game {
    private RestDeck $restDeck;
    private PlayDeck $playDeck;
    private array $players = []; 
    private const CARDS_PER_PLAYER = 7;

    public function __construct(array $players) {
        foreach ($players as $playerName) {
            $this->players[] = new Player($playerName);
        }
        fwrite(STDOUT, "Starting game with " . implode(', ', $players) . "\n");

        $this->restDeck = new RestDeck();
        $this->restDeck->shuffle();
        $this->dealCards();
        $this->printHands();
        $this->playDeck = new PlayDeck($this->restDeck->drawCard());
        $this->printTopCard();
    }

    private function dealCards(): void {
        for ($i = 0; $i < self::CARDS_PER_PLAYER; $i++) {
            foreach ($this->players as $player) {
                $card = $this->restDeck->drawCard();
                if ($card) {
                    $player->addCard($card);
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
            $topCard = $this->playDeck->getTopCard();
            if ($player->canPlayCard($topCard)) {
                $card = $player->playCard();
                $this->playDeck->addCard($card);
                fwrite(STDOUT, "{$player->getName()} plays {$topCard->getSuit()}{$topCard->getRank()}\n");
                if ($player->checkCardsRemaining() == 0) {
                  fwrite(STDOUT, "{$player->getName()} has won\n");
                  return;
                }
            }
            else {
                $card = $this->restDeck->drawCard();
                if ($card) {
                    $player->takeCard($card);
                    fwrite(STDOUT, "{$player->getName()} does not have a suitable card, taking from deck {$card->getSuit()}{$card->getRank()} \n", );
                }
            }
        }
      }
    }
}

function main() {
  $game = new Game(["Oleg", "John", "Jane", "Jim"]);
  $game->playGame();
}

main();