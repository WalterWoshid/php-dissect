<?php

namespace Dissect\Parser\LALR1;

use Dissect\Parser\Exception\UnexpectedTokenException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    protected ArithLexer $lexer;
    protected Parser $parser;

    protected function setUp(): void
    {
        $this->lexer = new ArithLexer();
        $this->parser = new Parser(new ArithGrammar());
    }

    #[Test]
    public function parserShouldProcessTheTokenStreamAndUseGrammarCallbacksForReductions()
    {
        $this->assertEquals(-2, $this->parser->parse($this->lexer->lex(
            '-1 - 1')));

        $this->assertEquals(11664, $this->parser->parse($this->lexer->lex(
            '6 ** (1 + 1) ** 2 * (5 + 4)')));

        $this->assertEquals(-4, $this->parser->parse($this->lexer->lex(
            '3 - 5 - 2')));

        $this->assertEquals(262144, $this->parser->parse($this->lexer->lex(
            '4 ** 3 ** 2')));
    }

    #[Test]
    public function parserShouldThrowAnExceptionOnInvalidInput()
    {
        try {
            $this->parser->parse($this->lexer->lex('6 ** 5 3'));
            $this->fail('Expected an UnexpectedTokenException.');
        } catch (UnexpectedTokenException $e) {
            $this->assertEquals('INT', $e->getToken()->getType());
            $this->assertEquals(array('$eof', '+', '-', '*', '/', '**', ')'), $e->getExpected());
            $this->assertEquals(<<<EOT
Unexpected 3 (INT) at line 1.

Expected one of \$eof, +, -, *, /, **, ).
EOT
            , $e->getMessage());
        }
    }
}
