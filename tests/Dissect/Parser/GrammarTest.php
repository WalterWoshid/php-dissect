<?php

namespace Dissect\Parser;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GrammarTest extends TestCase
{
    protected ExampleGrammar $grammar;

    protected function setUp(): void
    {
        $this->grammar = new ExampleGrammar();
    }

    #[Test]
    public function ruleAlternativesShouldHaveTheSameName()
    {
        $rules = $this->grammar->getRules();

        $this->assertEquals('Foo', $rules[1]->getName());
        $this->assertEquals('Foo', $rules[2]->getName());
    }

    #[Test]
    public function theGrammarShouldBeAugmentedWithAStartRule()
    {
        $this->assertEquals(
            Grammar::START_RULE_NAME,
            $this->grammar->getStartRule()->getName()
        );

        $this->assertEquals(
            array('Foo'),
            $this->grammar->getStartRule()->getComponents()
        );
    }

    #[Test]
    public function shouldReturnAlternativesGroupedByName()
    {
        $rules = $this->grammar->getGroupedRules();
        $this->assertCount(2, $rules['Foo']);
    }

    #[Test]
    public function nonterminalsShouldBeDetectedFromRuleNames()
    {
        $this->assertTrue($this->grammar->hasNonterminal('Foo'));
    }
}
