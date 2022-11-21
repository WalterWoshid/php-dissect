<?php

namespace Dissect\Lexer\TokenStream;

use Dissect\Lexer\CommonToken;
use OutOfBoundsException;
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

    /**
     * @test
     */
    public function theCursorShouldBeOnFirstTokenByDefault()
    {
        $this->assertEquals('6', $this->stream->getCurrentToken()->getValue());
    }

    /**
     * @test
     */
    public function getPositionShouldReturnCurrentPosition()
    {
        $this->stream->seek(2);
        $this->stream->next();

        $this->assertEquals(3, $this->stream->getPosition());
    }

    /**
     * @test
     */
    public function lookAheadShouldReturnTheCorrectToken()
    {
        $this->assertEquals('5', $this->stream->lookAhead(2)->getValue());
    }

    /**
     * @test
     */
    public function lookAheadShouldThrowAnExceptionWhenInvalid()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->stream->lookAhead(15);
    }

    /**
     * @test
     */
    public function getShouldReturnATokenByAbsolutePosition()
    {
        $this->assertEquals('3', $this->stream->get(4)->getValue());
    }

    /**
     * @test
     */
    public function getShouldThrowAnExceptionWhenInvalid()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->stream->get(15);
    }

    /**
     * @test
     */
    public function moveShouldMoveTheCursorByToAnAbsolutePosition()
    {
        $this->stream->move(2);
        $this->assertEquals('5', $this->stream->getCurrentToken()->getValue());
    }

    /**
     * @test
     */
    public function moveShouldThrowAnExceptionWhenInvalid()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->stream->move(15);
    }

    /**
     * @test
     */
    public function seekShouldMoveTheCursorByRelativeOffset()
    {
        $this->stream->seek(4);
        $this->assertEquals('3', $this->stream->getCurrentToken()->getValue());
    }

    /**
     * @test
     */
    public function seekShouldThrowAnExceptionWhenInvalid()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->stream->seek(15);
    }

    /**
     * @test
     */
    public function nextShouldMoveTheCursorOneTokenAhead()
    {
        $this->stream->next();
        $this->assertEquals('PLUS', $this->stream->getCurrentToken()->getType());

        $this->stream->next();
        $this->assertEquals('5', $this->stream->getCurrentToken()->getValue());
    }

    /**
     * @test
     */
    public function nextShouldThrowAnExceptionWhenAtTheEndOfTheStream()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->stream->seek(4);
        $this->stream->next();
    }
}
