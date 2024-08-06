<?php

namespace Dissect\Lexer;

use LogicException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class StatefulLexerTest extends TestCase
{
    protected StatefulLexer $lexer;

    protected function setUp(): void
    {
        $this->lexer = new StatefulLexer();
    }

    #[Test]
    public function addingNewTokenShouldThrowAnExceptionWhenNoStateIsBeingBuilt()
    {
        $this->expectExceptionMessage("Define a lexer state first.");
        $this->expectException(LogicException::class);
        $this->lexer->regex('WORD', '/[a-z]+/');
    }

    #[Test]
    public function anExceptionShouldBeThrownOnLexingWithoutAStartingState()
    {
        $this->expectException(LogicException::class);
        $this->lexer->state('root');
        $this->lexer->lex('foo');
    }

    #[Test]
    public function theStateMechanismShouldCorrectlyPushAndPopStatesFromTheStack()
    {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $this->lexer->state('root')
            ->regex('WORD', '/[a-z]+/')
            ->regex('WS', "/[ \r\n\t]+/")
            ->token('"')->action('string')
            ->skip('WS');

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $this->lexer->state('string')
            ->regex('STRING_CONTENTS', '/(\\\\"|[^"])+/')
            ->token('"')->action(StatefulLexer::POP_STATE);

        $this->lexer->start('root');

        $stream = $this->lexer->lex('foo bar "long \\" string" baz quux');

        $this->assertCount(8, $stream);
        $this->assertEquals('STRING_CONTENTS', $stream->get(3)->getType());
        $this->assertEquals('long \\" string', $stream->get(3)->getValue());
        $this->assertEquals('quux', $stream->get(6)->getValue());
    }

    #[Test]
    public function defaultActionShouldBeNop()
    {
        $this->lexer->state('root')
            ->regex('WORD', '/[a-z]+/')
            ->regex('WS', "/[ \r\n\t]+/")
            ->skip('WS');

        $this->lexer->state('string');

        $this->lexer->start('root');

        $stream = $this->lexer->lex('foo bar');
        $this->assertEquals(3, $stream->count());
    }
}
