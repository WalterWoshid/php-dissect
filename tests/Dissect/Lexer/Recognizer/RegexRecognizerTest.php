<?php

namespace Dissect\Lexer\Recognizer;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RegexRecognizerTest extends TestCase
{
    #[Test]
    public function recognizerShouldMatchAndPassTheValueByReference()
    {
        $recognizer = new RegexRecognizer('/[a-z]+/');
        $result = $recognizer->match('lorem ipsum', $value);

        $this->assertTrue($result);
        $this->assertNotNull($value);
        $this->assertEquals('lorem', $value);
    }

    #[Test]
    public function recognizerShouldFailAndTheValueShouldStayNull()
    {
        $recognizer = new RegexRecognizer('/[a-z]+/');
        $result = $recognizer->match('123 456', $value);

        $this->assertFalse($result);
        $this->assertNull($value);
    }

    #[Test]
    public function recognizerShouldFailIfTheMatchIsNotAtTheBeginningOfTheString()
    {
        $recognizer = new RegexRecognizer('/[a-z]+/');
        $result = $recognizer->match('234 class', $value);

        $this->assertFalse($result);
        $this->assertNull($value);
    }
}
