<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Card.php';
require_once __DIR__ . '/../src/Player.php';

class PlayerTest extends TestCase
{
    public function testPlayerCanTakeCard(): void
    {
        $player = new Player('TestPlayer');
        $card = new Card('A', '♥');
        
        $player->takeCard($card);
        
        $this->assertEquals(1, $player->checkCardsRemaining());
    }
    
    public function testPlayerCanFindMatchingCard(): void
    {
        $player = new Player('TestPlayer');
        $player->takeCard(new Card('A', '♥'));
        $player->takeCard(new Card('K', '♠'));
        
        $topCard = new Card('A', '♠');
        $playedCard = $player->findAndPlayCard($topCard);
        
        $this->assertNotNull($playedCard);
        $this->assertEquals('♥A', $playedCard->toString());
    }
    
    public function testPlayerReturnsNullWhenNoMatchingCard(): void
    {
        $player = new Player('TestPlayer');
        $player->takeCard(new Card('K', '♠'));
        
        $topCard = new Card('A', '♥');
        $playedCard = $player->findAndPlayCard($topCard);
        
        $this->assertNull($playedCard);
    }
    
    public function testCheckCardsRemainingReturnsCorrectCount(): void
    {
        $player = new Player('TestPlayer');
        $player->takeCard(new Card('A', '♥'));
        $player->takeCard(new Card('K', '♠'));
        $player->takeCard(new Card('Q', '♦'));
        
        $this->assertEquals(3, $player->checkCardsRemaining());
    }
}