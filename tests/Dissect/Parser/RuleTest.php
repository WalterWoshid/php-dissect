<?php

namespace Dissect\Parser;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{

    #[Test]
    public function getComponentShouldReturnNullIfAskedForComponentOutOfRange()
    {
        $r = new Rule(1, 'Foo', ['x', 'y']);
        $this->assertEquals('y', $r->getComponent(1));
        $this->assertNull($r->getComponent(2));
    }
}
