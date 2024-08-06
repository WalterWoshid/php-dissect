<?php

namespace Dissect\Lexer\Recognizer;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SimpleRecognizerTest extends TestCase
{
    #[Test]
    public function recognizerShouldMatchAndPassTheValueByReference()
    {
        $recognizer = new SimpleRecognizer('class');
        $result = $recognizer->match('class lorem ipsum', $value);

        $this->assertTrue($result);
        $this->assertNotNull($value);
        $this->assertEquals('class', $value);
    }

    #[Test]
    public function recognizerShouldFailAndTheValueShouldStayNull()
    {
        $recognizer = new SimpleRecognizer('class');
        $result = $recognizer->match('lorem ipsum', $value);

        $this->assertFalse($result);
        $this->assertNull($value);
    }
}
