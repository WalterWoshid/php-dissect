<?php

namespace Dissect\Lexer;

use Dissect\Lexer\Exception\RecognitionException;
use Dissect\Parser\Parser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AbstractLexerTest extends TestCase
{
    protected ?StubLexer $lexer = null;

    public function setUp(): void
    {
        $this->lexer = new StubLexer();
    }

    #[Test]
    public function lexShouldDelegateToExtractTokenUpdatingTheLineAndOffsetAccordingly()
    {
        $stream = $this->lexer->lex("ab\nc");

        $this->assertEquals('a', $stream->getCurrentToken()->getValue());
        $this->assertEquals(1, $stream->getCurrentToken()->getLine());
        $stream->next();

        $this->assertEquals('b', $stream->getCurrentToken()->getValue());
        $this->assertEquals(1, $stream->getCurrentToken()->getLine());
        $stream->next();

        $this->assertEquals("\n", $stream->getCurrentToken()->getValue());
        $this->assertEquals(1, $stream->getCurrentToken()->getLine());
        $stream->next();

        $this->assertEquals('c', $stream->getCurrentToken()->getValue());
        $this->assertEquals(2, $stream->getCurrentToken()->getLine());
    }

    #[Test]
    public function lexShouldAppendAnEofTokenAutomatically()
    {
        $stream = $this->lexer->lex("abc");
        $stream->seek(3);

        $this->assertEquals(Parser::EOF_TOKEN_TYPE, $stream->getCurrentToken()->getType());
        $this->assertEquals(1, $stream->getCurrentToken()->getLine());
    }

    #[Test]
    public function lexShouldThrowAnExceptionOnAnUnrecognizableToken()
    {
        try {
            $this->lexer->lex("abcd");
            $this->fail('Expected a RecognitionException.');
        } catch (RecognitionException $e) {
            $this->assertEquals(1, $e->getSourceLine());
        }
    }

    #[Test]
    public function lexShouldNormalizeLineEndingsBeforeLexing()
    {
        $stream = $this->lexer->lex("a\r\nb");
        $this->assertEquals("\n", $stream->get(1)->getValue());
    }

    #[Test]
    public function lexShouldSkipTokensIfToldToDoSo()
    {
        $stream = $this->lexer->lex('aeb');
        $this->assertNotEquals('e', $stream->get(1)->getType());
    }
}
