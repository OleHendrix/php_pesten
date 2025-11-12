<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/game_functions.php';

class GameFunctionsTest extends TestCase
{
    public function testCreateRestDeckReturns52Cards(): void
    {
        $deck = createRestDeck();
        
        $this->assertCount(52, $deck);
    }
    
    public function testDrawCardRemovesCardFromDeck(): void
    {
        $deck = createRestDeck();
        
        $result = drawCard($deck);
        
        $this->assertNotNull($result['card']);
        $this->assertCount(51, $result['deck']);
    }
    
    public function testDrawCardFromEmptyDeckReturnsNull(): void
    {
        $emptyDeck = [];
        
        $result = drawCard($emptyDeck);
        
        $this->assertNull($result['card']);
        $this->assertEmpty($result['deck']);
    }
    
    public function testAddCardToPlayDeckAddsCardToFront(): void
    {
        $playDeck = [new Card('A', '♥')];
        $newCard = new Card('K', '♠');
        
        $playDeck = addCardToPlayDeck($playDeck, $newCard);
        
        $this->assertCount(2, $playDeck);
        $this->assertEquals('♠K', $playDeck[0]->toString());
    }
    
    public function testGetTopCardReturnsFirstCard(): void
    {
        $playDeck = [
            new Card('K', '♠'),
            new Card('A', '♥')
        ];
        
        $topCard = getTopCard($playDeck);
        
        $this->assertEquals('♠K', $topCard->toString());
    }
    
    public function testDealCardsDistributesCorrectNumberOfCards(): void
    {
        $players = [
            new Player('Player1'),
            new Player('Player2')
        ];
        $restDeck = createRestDeck();
        
        $restDeck = dealCards($players, $restDeck, 7);
        
        $this->assertEquals(7, $players[0]->checkCardsRemaining());
        $this->assertEquals(7, $players[1]->checkCardsRemaining());
        $this->assertCount(38, $restDeck); // 52 - (2 * 7) = 38
    }
}