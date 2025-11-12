<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Card.php';

class CardTest extends TestCase
{
    public function testCardToStringReturnsCorrectFormat(): void
    {
        $card = new Card('A', '♥');
        
        $this->assertEquals('♥A', $card->toString());
    }
    
    public function testMatchesCardReturnsTrueWhenRankMatches(): void
    {
        $card1 = new Card('A', '♥');
        $card2 = new Card('A', '♠');
        
        $this->assertTrue($card1->matchesCard($card2));
    }
    
    public function testMatchesCardReturnsTrueWhenSuitMatches(): void
    {
        $card1 = new Card('A', '♥');
        $card2 = new Card('K', '♥');
        
        $this->assertTrue($card1->matchesCard($card2));
    }
    
    public function testMatchesCardReturnsFalseWhenNeitherMatches(): void
    {
        $card1 = new Card('A', '♥');
        $card2 = new Card('K', '♠');
        
        $this->assertFalse($card1->matchesCard($card2));
    }
}