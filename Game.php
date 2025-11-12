<?php
require_once 'RestDeck.php';
require_once 'PlayDeck.php';
require_once 'Player.php';

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
            $topCard = $this->playDeck->getTopCard();
            $card = $player->findAndPlayCard($topCard);
            if ($card) {
                $this->playDeck->addCard($card);
                fwrite(STDOUT, "{$player->getName()} plays {$card->getSuit()}{$card->getRank()}\n");
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