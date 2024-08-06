<?php

namespace Dissect\Parser\LALR1\Analysis;

use Dissect\Parser\Rule;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    #[Test]
    public function stateShouldKeepItemsByRuleNumberAndPosition()
    {
        $item1 = new Item(new Rule(1, 'E', ['E', '+', 'T']), 0);
        $state = new State(0, array($item1));

        $this->assertSame($item1, $state->get(1, 0));

        $item2 = new Item(new Rule(2, 'T', ['T', '+', 'F']), 0);
        $state->add($item2);

        $this->assertSame($item2, $state->get(2, 0));
    }
}
