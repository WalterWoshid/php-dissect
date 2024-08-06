<?php

namespace Dissect\Lexer\TokenStream;

use Dissect\Lexer\CommonToken;
use OutOfBoundsException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ArrayTokenStreamTest extends TestCase
{
    protected ?ArrayTokenStream $stream = null;

    protected function setUp(): void
    {
        $this->stream = new ArrayTokenStream([
            new CommonToken('INT', '6', 1),
            new CommonToken('PLUS', '+', 1),
            new CommonToken('INT', '5', 1),
            new CommonToken('MINUS', '-', 1),
            new CommonToken('INT', '3', 1),
        ]);
    }

    #[Test]
    public function theCursorShouldBeOnFirstTokenByDefault()
    {
        $this->assertEquals('6', $this->stream->getCurrentToken()->getValue());
    }

    #[Test]
    public function getPositionShouldReturnCurrentPosition()
    {
        $this->stream->seek(2);
        $this->stream->next();

        $this->assertEquals(3, $this->stream->getPosition());
    }

    #[Test]
    public function lookAheadShouldReturnTheCorrectToken()
    {
        $this->assertEquals('5', $this->stream->lookAhead(2)->getValue());
    }

    #[Test]
    public function lookAheadShouldThrowAnExceptionWhenInvalid()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->stream->lookAhead(15);
    }

    #[Test]
    public function getShouldReturnATokenByAbsolutePosition()
    {
        $this->assertEquals('3', $this->stream->get(4)->getValue());
    }

    #[Test]
    public function getShouldThrowAnExceptionWhenInvalid()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->stream->get(15);
    }

    #[Test]
    public function moveShouldMoveTheCursorByToAnAbsolutePosition()
    {
        $this->stream->move(2);
        $this->assertEquals('5', $this->stream->getCurrentToken()->getValue());
    }

    #[Test]
    public function moveShouldThrowAnExceptionWhenInvalid()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->stream->move(15);
    }

    #[Test]
    public function seekShouldMoveTheCursorByRelativeOffset()
    {
        $this->stream->seek(4);
        $this->assertEquals('3', $this->stream->getCurrentToken()->getValue());
    }

    #[Test]
    public function seekShouldThrowAnExceptionWhenInvalid()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->stream->seek(15);
    }

    #[Test]
    public function nextShouldMoveTheCursorOneTokenAhead()
    {
        $this->stream->next();
        $this->assertEquals('PLUS', $this->stream->getCurrentToken()->getType());

        $this->stream->next();
        $this->assertEquals('5', $this->stream->getCurrentToken()->getValue());
    }

    #[Test]
    public function nextShouldThrowAnExceptionWhenAtTheEndOfTheStream()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->stream->seek(4);
        $this->stream->next();
    }
}
